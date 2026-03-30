<?php

namespace App\Payments\Actions;

use Exception;
use Throwable;
use App\Models\Donation;
use App\Enums\Payments\PaymentGatewaysEnum;
use App\Enums\Payments\PaymentStatusEnum;
use App\Http\Requests\StoreDonationRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class StripeDonate
{
	// Customer page after successfull payment
	const SUCCESS_URL = '/donate/success?csid={CHECKOUT_SESSION_ID}';
	// Payu notifications
	const NOTIFY_URL = '/api/notify/stripe/donate';
	// Api locale
	const LOCALE = 'pl';

	public $currency_codes = ['pln', 'usd', 'eur'];

	public $webhook_ips = [
		'3.18.12.63',
		'3.130.192.231',
		'13.235.14.237',
		'13.235.122.149',
		'18.211.135.69',
		'35.154.171.200',
		'52.15.183.38',
		'54.88.130.119',
		'54.88.130.237',
		'54.187.174.169',
		'54.187.205.235',
		'54.187.216.72',
	];

	/**
	 * Allow only this payment methods.
	 * Disabled here set methods from strip panel.
	 *
	 * @return void
	 */
	public function allowedPaymentMethods()
	{
		return ['card', 'paypal', 'blik', 'p24'];
	}

	protected function secretKey(): string
	{
		if (empty(env('STRIPE_SECRET'))) {
			throw new Exception("Empty stripe secret", 422);
		}

		return env('STRIPE_SECRET');
	}

	protected function webhookKey(): string
	{
		if (empty(env('STRIPE_WEBHOOK_SECRET'))) {
			throw new Exception("Empty stripe webhook secret", 422);
		}

		return env('STRIPE_WEBHOOK_SECRET');
	}

	protected function currencyName(): string
	{
		$currency = env('STRIPE_CURRENCY', 'pln');

		return in_array($currency, $this->currency_codes) ? strtolower($currency) : 'pln';
	}

	protected function allowedIps(): array
	{
		return $this->webhook_ips;
	}

	protected function logInSandbox(): bool
	{
		return str_contains($this->secretKey(), '_test_');
	}

	/**
	 * Create payment order array
	 *
	 * @param Donation $donation
	 * @return array
	 */
	public function createOrder(Donation $donation): array
	{
		// Order
		$order = [
			// Payment methods
			// 'payment_method_types' => $this->allowedPaymentMethods(),
			// Custom price
			'line_items' => [[
				'price_data' => [
					'unit_amount' => $donation->amount,
					'currency' => $this->currencyName(),
					'product_data' => [
						'name' => 'Donate',
					],
				],
				'quantity' => 1,
			]],
			// Manual confirmation, status requires_capture (cards only),
			// 'payment_intent_data' => [
			// 	'capture_method' => 'manual',
			// ],
			// Details
			'payment_intent_data' => [
				'capture_method' => 'automatic_async',
				'metadata' => [
					'payment_id' => $donation->payment_id,
					'email' => $donation->email,
					'name' => $donation->name,
					'last_name' => $donation->last_name ?? $donation->name,
					'amount' => $donation->amount,
					'message' => $donation->message,
					'currency' => $this->currencyName(),
					'ip' => request()->ip(),
				],
			],
			// Redirect
			'after_completion' => [
				'type' => 'redirect',
				'redirect' => [
					'url' => request()->getSchemeAndHttpHost() . self::SUCCESS_URL
				]
			],
		];

		return $order;
	}

	/**
	 * Create payment order array
	 *
	 * @param Donation $donation
	 * @return array
	 */
	public function createOrderCheckout(Donation $donation): array
	{
		// For checkout
		// 'locked_prefilled_email' => $donation->email,
		// 'prefilled_email' => $donation->email,
		// 'prefilled_promo_code' => 'ABX123',
		// 'locale' => 'pl',

		// Order checkout
		$order = [
			// Payment methods
			'customer_email' => $donation->email,
			'locale' => strtolower(self::LOCALE),
			// 'payment_method_types' => $this->allowedPaymentMethods(),
			'mode' => 'payment',
			// Custom price
			'line_items' => [
				[
					'price_data' => [
						// 'tax_behavior' => 'exclusive',
						// 'recurring' => ['interval' => 'month', 'interval_count' => 3],
						'unit_amount' => $donation->amount,
						'currency' => $this->currencyName(),
						'product_data' => [
							'name' => 'Donate',
							'images' => [
								request()->getSchemeAndHttpHost() . '/default/donate/logo.png',
							],
							'unit_label' => 'szt.',
						],
					],
					'quantity' => 1,
				]
			],
			// Manual confirmation, status requires_capture (cards only),
			// 'payment_intent_data' => [
			// 	'capture_method' => 'manual',
			// ],
			// Details
			'payment_intent_data' => [
				'metadata' => [
					'payment_id' => $donation->payment_id,
					'email' => $donation->email,
					'name' => $donation->name,
					'last_name' => $donation->last_name ?? $donation->name,
					'amount' => $donation->amount,
					'message' => $donation->message,
					'currency' => $this->currencyName(),
					'ip' => request()->ip(),
				],
			],
			'metadata' => [
				'payment_id' => $donation->payment_id,
				'email' => $donation->email,
				'name' => $donation->name,
				'last_name' => $donation->last_name ?? $donation->name,
				'amount' => $donation->amount,
				'message' => $donation->message,
				'currency' => $this->currencyName(),
				'ip' => request()->ip(),
			],
			// Redirect
			'success_url' => request()->getSchemeAndHttpHost() . self::SUCCESS_URL,
			'cancel_url' => request()->getSchemeAndHttpHost() . self::SUCCESS_URL . '&error=501',
			'branding_settings' => [
				// 'button_color' => '#55cc55',
				// 'display_name' => 'Welcome',
				// 'logo' => [
				// 	'type' => 'url',
				// 	'url' => 'https://img.freepik.com/free-vector/hand-drawn-flat-design-anarchy-symbol_23-2149244363.jpg'
				// ]
			],
			// Tax (Paid ble !!!)
			// 'automatic_tax' => ['enabled' => true],
			// Shipping (Paid ble !!!)
			// 'shipping_address_collection' => ['allowed_countries' => ['PL', 'US', 'CA']],
			// 'shipping_address_collection' => ['allowed_countries' => ['PL']],
			// 'shipping_options' => [
			// 	[
			// 		'shipping_rate_data' => [
			// 			'type' => 'fixed_amount',
			// 			'fixed_amount' => [
			// 				'amount' => 0,
			// 				'currency' => $this->currencyName(),
			// 			],
			// 			'display_name' => 'Online delivery',
			// 			'delivery_estimate' => [
			// 				'minimum' => [
			// 					'unit' => 'business_day',
			// 					'value' => 1,
			// 				],
			// 				'maximum' => [
			// 					'unit' => 'business_day',
			// 					'value' => 1,
			// 				],
			// 			],
			// 		],
			// 	],
			// 	[
			// 		'shipping_rate_data' => [
			// 			// 'tax_behavior' => 'exclusive',
			// 			// 'tax_code' => 'txcd_92010001',
			// 			'type' => 'fixed_amount',
			// 			'fixed_amount' => [
			// 				'amount' => 1500,
			// 				'currency' => $this->currencyName(),
			// 			],
			// 			'display_name' => 'Next day air',
			// 			'delivery_estimate' => [
			// 				'minimum' => [
			// 					'unit' => 'business_day',
			// 					'value' => 1,
			// 				],
			// 				'maximum' => [
			// 					'unit' => 'business_day',
			// 					'value' => 1,
			// 				],
			// 			],
			// 		],
			// 	],
			// ],
		];

		return $order;
	}

	/**
	 * Create payment link for donation with BasicAuth
	 *
	 * @param Donation $donation
	 * @return array
	 */
	public function donate(Donation $donation): string|null
	{
		$stripe = new \Stripe\StripeClient($this->secretKey());

		$paymentLink = $stripe->paymentLinks->create($this->createOrder($donation));

		// Success 200 or 302
		if ($paymentLink instanceof \Stripe\PaymentLink) {
			// Update
			$donation->external_id = $paymentLink->id;
			$donation->url = $paymentLink->url;
			$donation->save();

			// extOrderId, orderId, redirectUri
			return $paymentLink->url;
		} else {
			return null;
		}
	}

	/**
	 * Create payment link for donation with BasicAuth
	 *
	 * @param Donation $donation
	 * @return array
	 */
	public function donateCheckout(Donation $donation): string|null
	{
		$stripe = new \Stripe\StripeClient($this->secretKey());

		$paymentLink = $stripe->checkout->sessions->create($this->createOrderCheckout($donation));

		// Success 200 or 302
		if ($paymentLink instanceof \Stripe\Checkout\Session) {
			// Update
			$donation->external_id = $paymentLink->id;
			$donation->url = $paymentLink->url;
			$donation->save();

			// extOrderId, orderId, redirectUri
			return $paymentLink->url;
		} else {
			return null;
		}
	}

	/**
	 * Order details with BasicAuth
	 *
	 * @param string $id
	 * @return array
	 */
	public function orderDetails($id): array
	{
		$stripe = new \Stripe\StripeClient($this->secretKey());

		$pi = $stripe->paymentIntents->retrieve($id, []);

		return $pi->toArray();
	}

	/**
	 * Paymethods with BasicAuth
	 *
	 * @param string $id
	 * @return array
	 */
	public function paymethods(): array
	{
		$stripe = new \Stripe\StripeClient($this->secretKey());

		$pm = $stripe->paymentMethods->all([
			'limit' => 100,
			// 'type' => 'card',
			// 'customer' => 'cus_9s6XKzkNRiz8i3',
		]);

		return $pm->toArray();
	}

	/**
	 * Create donation with payment link without bearer token (BasicAuth)
	 * only PosId client_id and client_secret required.
	 * (Controller method)
	 *
	 * @param StoreDonationRequest $request
	 * @return Response Return http response with status 200 or 422.
	 */
	function createPaymentCheckout(StoreDonationRequest $request)
	{
		try {
			if (empty(env('STRIPE_SECRET'))) {
				throw new Exception("Empty stripe secret", 422);
			}

			$valid = $request->validated();
			$valid['amount'] = $this->toCents($valid['amount']);
			$valid['payment_id'] = Str::uuid();
			$valid['status'] = PaymentStatusEnum::NEW->value;
			$valid['gateway'] = PaymentGatewaysEnum::STRIPE->value;
			$valid['currency'] = $this->currencyName();

			$donation = Donation::create($valid);

			// Create payment link
			$pay = new StripeDonate();
			$url = $pay->donateCheckout($donation);

			return response()->json([
				'message' => 'Created',
				'redirect' => $url,
			], 200);
		} catch (Throwable $e) {
			report($e);

			return response()->json([
				'error' => $e->getMessage()
			], 422);
		}
	}

	/**
	 * Create donation with payment link without bearer token (BasicAuth)
	 * only PosId client_id and client_secret required.
	 * (Controller method)
	 *
	 * @param StoreDonationRequest $request
	 * @return Response Return http response with status 200 or 422.
	 */
	function createPayment(StoreDonationRequest $request)
	{
		try {
			if (empty(env('STRIPE_SECRET'))) {
				throw new Exception("Empty stripe secret", 422);
			}

			$valid = $request->validated();
			$valid['amount'] = $this->toCents($valid['amount']);
			$valid['payment_id'] = Str::uuid();
			$valid['status'] = PaymentStatusEnum::NEW->value;
			$valid['gateway'] = PaymentGatewaysEnum::STRIPE->value;
			$valid['currency'] = $this->currencyName();

			$donation = Donation::create($valid);

			// Create payment link
			$pay = new StripeDonate();
			$url = $pay->donate($donation);

			return response()->json([
				'message' => 'Created',
				'redirect' => $url,
			], 200);
		} catch (Throwable $e) {
			report($e);

			return response()->json([
				'error' => $e->getMessage()
			], 422);
		}
	}

	/**
	 * Get payment and refund notifications from payu.
	 * (Controller method)
	 *
	 * @return Response Return http response with status 200 or 422.
	 */
	function checkNotification()
	{
		try {
			if (!in_array(request()->ip(), $this->allowedIps())) {
				throw new Exception('Notify invalid ip address', 422);
			}

			$stripe = new \Stripe\StripeClient($this->secretKey());
			$sig_header = request()->header('Stripe-Signature'); // Header Stripe-Signature
			$payload = request()->getContent();
			$event = null;

			// (Errors) In basil api works in clover api disable it
			// $payload = $this->jsonEncode(json_decode($payload));

			if ($this->logInSandbox()) {
				Log::info('STRIPE_NOTIFY', [
					'timestamp' => time(),
					'signature' => $sig_header,
					'payload' => $payload,
				]);
			}

			try {
				$event = \Stripe\Webhook::constructEvent(
					$payload,
					$sig_header,
					$this->webhookKey()
				);
			} catch (\UnexpectedValueException $e) {
				return response()->json([
					'message' => $e->getMessage()
				], 400);
			} catch (\Stripe\Exception\SignatureVerificationException $e) {
				return response()->json([
					'message' => $e->getMessage()
				], 400);
			}

			$paymentIntent = null; // \Stripe\PaymentIntent
			$paymentMethod = null; // \Stripe\PaymentMethod
			$paymentLink = null;
			$subscription = null;
			$session = null;

			// Handle the event
			switch ($event->type) {
				case 'checkout.session.async_payment_failed':
					$session = $event->data->object;
				case 'checkout.session.async_payment_succeeded':
					$session = $event->data->object;
				case 'checkout.session.completed':
					$session = $event->data->object;
				case 'checkout.session.expired':
					$session = $event->data->object;

				case 'payment_intent.created': // new
					$paymentIntent = $event->data->object;
				case 'payment_intent.payment_failed': // failed
					$paymentIntent = $event->data->object;
				case 'payment_intent.canceled': // canceled
					$paymentIntent = $event->data->object;
				case 'payment_intent.succeeded': // completed
					$paymentIntent = $event->data->object;
				case 'payment_intent.processing': // pending
					$paymentIntent = $event->data->object;
				case 'payment_intent.amount_capturable_updated': // wiating_for_confirmation
					$paymentIntent = $event->data->object;
				case 'payment_intent.requires_action':
					$paymentIntent = $event->data->object;
				case 'payment_intent.partially_funded':
					$paymentIntent = $event->data->object;

				case 'payment_link.created':
					$paymentLink = $event->data->object;
				case 'payment_link.updated':
					$paymentLink = $event->data->object;

				case 'refund.created':
					$refund = $event->data->object;
				case 'refund.updated':
					$refund = $event->data->object;

				case 'subscription_schedule.aborted':
					$subscription = $event->data->object;
				case 'subscription_schedule.canceled':
					$subscription = $event->data->object;
				case 'subscription_schedule.completed':
					$subscription = $event->data->object;
				case 'subscription_schedule.created':
					$subscription = $event->data->object;
				case 'subscription_schedule.expiring':
					$subscription = $event->data->object;
				case 'subscription_schedule.released':
					$subscription = $event->data->object;
				case 'subscription_schedule.updated':
					$subscription = $event->data->object;

				case 'payment_method.attached':
					$paymentMethod = $event->data->object;
					// ... handle other event types
				default:
					// Log to file
					if ($this->logInSandbox()) {
						Log::info('STRIPE_EVENT', [
							'intent_status' => $paymentIntent->status ?? null,
							$event->type => json_encode($event->data->object),
						]);
					}

					// Handle successful payment here Intent: id, amount, currency, status
					if ($paymentIntent instanceof \Stripe\PaymentIntent) {

						if (empty($paymentIntent->metadata->payment_id)) {
							throw new Exception("Invalid donation id");
						}

						$payment_id = $paymentIntent->metadata->payment_id;
						$donation = Donation::where('payment_id', $payment_id)->first();

						if ($donation instanceof Donation) {
							// Get from stripe
							$pi = $stripe->paymentIntents->retrieve($paymentIntent->id, []);
							if ($pi instanceof \Stripe\PaymentIntent) {

								if ($pi->status === 'succeeded') {
									$donation->status = PaymentStatusEnum::COMPLETED->value;
									$donation->external_id = $pi->id;
									$donation->save();
								}

								if ($pi->status === 'canceled') {
									$donation->status = PaymentStatusEnum::CANCELED->value;
									$donation->external_id = $pi->id;
									$donation->save();
								}

								if ($pi->status === 'requires_capture') {
									$donation->status = PaymentStatusEnum::WAITING->value;
									$donation->external_id = $pi->id;
									$donation->save();
								}

								// This payment attempt failed. Once **canceled** on the bank's website,
								// a subsequent bank payment may overwrite the status to **successful** if the
								// customer hasn't left the strip payment page or if they open the payment link again (after resending),
								// this status cannot be overwritten by **canceled**.
								if ($pi->status === 'requires_payment_method' && isset($pi->last_payment_error)) {
									// This payment attempt has failed
									$donation->status = PaymentStatusEnum::FAILED->value;
									$donation->external_id = $pi->id;
									$donation->save();
								}
							} else {
								throw new Exception("Invalid intent id", 422);
							}
						} else {
							throw new Exception("Invalid transaction id 👻👽🤡", 422);
						}
					}
			}

			return response()->json([
				'message' => 'Comfirmed'
			], 200);
		} catch (Throwable $e) {
			report($e);

			return response()->json([
				'message' => 'Not comfirmed'
			], 422);
		}
	}

	/**
	 * Encode json for payu
	 *
	 * @param array $arr
	 * @return string Json dtring
	 */
	public function jsonEncode($arr): string
	{
		return json_encode($arr, flags: JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
	}

	/**
	 * Decode json for payu
	 *
	 * @param string $json
	 * @return object
	 */
	public function jsonDecode($json): object
	{
		return json_decode($json, flags: JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
	}

	/**
	 * Change decimal to int amount
	 *
	 * @param float $decimal
	 * @return integer
	 */
	function toCents(float $decimal): int
	{
		return number_format($decimal * 100, 0, '.', '');
	}

	/**
	 * Change int amount to decimal
	 *
	 * @param int $amount
	 * @return float
	 */
	public function toDecimal($amount)
	{
		return number_format(($amount / 100), 2, '.', '');
	}

	public static function createWebhook()
	{
		\Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

		$endpoint = \Stripe\WebhookEndpoint::create([
			'url' => request()->getSchemeAndHttpHost() . self::NOTIFY_URL,
			'enabled_events' => [
				'payment_link.created',
				'payment_link.updated',
				'payment_intent.created',
				'payment_intent.canceled',
				'payment_intent.succeeded',
				'payment_intent.processing',
				'payment_intent.payment_failed',
				'payment_intent.requires_action',
				'payment_intent.partially_funded',
				'payment_intent.amount_capturable_updated',
				'checkout.session.async_payment_failed',
				'checkout.session.async_payment_succeeded',
				'checkout.session.completed',
				'checkout.session.expired',
			],
		]);

		return $endpoint;
	}
}
