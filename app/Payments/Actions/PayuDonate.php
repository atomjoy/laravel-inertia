<?php

namespace App\Payments\Actions;

use Exception;
use Throwable;
use App\Models\Donation;
use App\Enums\Payments\PaymentGatewaysEnum;
use App\Enums\Payments\PaymentStatusEnum;
use App\Http\Requests\StoreDonationRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PayuDonate
{
	// Customer page after successfull payment
	const SUCCESS_URL = '/donate/success';
	// Payu notifications
	const NOTIFY_URL = '/api/notify/payu/donate';
	// Api authorize
	const AUTH_COUNTRY = 'pl';
	// Api sandbox
	const SANDBOX_URL = 'https://secure.snd.payu.com';
	// Api production
	const PRODUCTION_URL = 'https://secure.payu.com';
	// Response code
	const SUCCESS = 'SUCCESS';

	public $currency_codes = ['pln', 'usd', 'eur'];

	public $production_ips = ['185.68.12.10', '185.68.12.11', '185.68.12.12', '185.68.12.26', '185.68.12.27', '185.68.12.28'];

	public $sandbox_ips = ['185.68.14.10', '185.68.14.11', '185.68.14.12', '185.68.14.26', '185.68.14.27', '185.68.14.28'];

	protected ?PayuToken $payuToken = null;

	protected function getToken(): string
	{
		if ($this->payuToken instanceof PayuToken) {
			return $this->payuToken->access_token;
		} else {
			throw new Exception("Empty token", 422);
		}
	}

	protected function merchantPosId(): string
	{
		if (empty(env('PAYU_CLIENT_ID')) || empty(env('PAYU_CLIENT_SECRET'))) {
			throw new Exception("Empty payu PosId credentials", 422);
		}

		return env('PAYU_CLIENT_ID');
	}

	protected function merchantSecondKey(): string
	{
		if (empty(env('PAYU_CLIENT_ID')) || empty(env('PAYU_CLIENT_SECRET'))) {
			throw new Exception("Empty payu PosId credentials", 422);
		}

		return env('PAYU_CLIENT_SECRET');
	}

	protected function authCredentials($grant_type = 'client_credentials'): array
	{
		if (empty(env('PAYU_OAUTH_CLIENT_ID')) || empty(env('PAYU_OAUTH_CLIENT_SECRET'))) {
			throw new Exception("Empty payu oAuth credentials", 422);
		}

		return [
			'grant_type' => $grant_type,
			'client_id' => env('PAYU_OAUTH_CLIENT_ID'),
			'client_secret' => env('PAYU_OAUTH_CLIENT_SECRET'),
		];
	}

	protected function cacheKeyName(): string
	{
		return 'payu_token_' . md5(env('PAYU_OAUTH_CLIENT_ID', 'key'));
	}

	protected function currencyName(): string
	{
		$currency = env('PAYU_CURRENCY', 'PLN');

		return in_array($currency, $this->currency_codes) ? strtoupper($currency) : 'PLN';
	}

	protected function allowedIps(): array
	{
		if (env('PAYU_SANDBOX', false) == true) {
			return $this->sandbox_ips;
		} else {
			return $this->production_ips;
		}
	}

	protected function logInSandbox($data): void
	{
		if (env('PAYU_SANDBOX', false) == true) {
			Log::info('PAYU_NOTIFY', ['content' => $data]);
		}
	}

	protected function urlAuthorize(): string
	{
		if (env('PAYU_SANDBOX', false) == true) {
			return self::SANDBOX_URL . '/' . self::AUTH_COUNTRY . '/standard/user/oauth/authorize';
		} else {
			return self::PRODUCTION_URL . '/' . self::AUTH_COUNTRY . '/standard/user/oauth/authorize';
		}
	}

	protected function urlOrders(): string
	{
		if (env('PAYU_SANDBOX', false) == true) {
			return self::SANDBOX_URL . '/api/v2_1/orders';
		} else {
			return self::PRODUCTION_URL . '/api/v2_1/orders';
		}
	}

	protected function urlOrderDetails($id): string
	{
		if (env('PAYU_SANDBOX', false) == true) {
			return self::SANDBOX_URL . '/api/v2_1/orders/' . $id;
		} else {
			return self::PRODUCTION_URL . '/api/v2_1/orders/' . $id;
		}
	}

	protected function urlPaymethods(): string
	{
		if (env('PAYU_SANDBOX', false) == true) {
			return self::SANDBOX_URL . '/api/v2_1/paymethods';
		} else {
			return self::PRODUCTION_URL . '/api/v2_1/paymethods';
		}
	}

	/**
	 * Authorize for bearer token with oAuth client_id and oAuth cilent_secret.
	 *
	 * @return void
	 */
	public function authorize(): void
	{
		// Get from cache or refresh
		if (Cache::store('file')->has($this->cacheKeyName())) {
			$this->payuToken = Cache::store('file')->get($this->cacheKeyName()) ?? null;
		} else {
			// Authorization
			$res = Http::asForm()
				->withoutRedirecting()->connectTimeout(10)->timeout(60)->acceptJson()
				->post($this->urlAuthorize(), $this->authCredentials());

			// Check data
			if ($res->successful()) {
				if (
					!empty($res->json('expires_in')) &&
					!empty($res->json('access_token')) &&
					!empty($res->json('grant_type')) &&
					!empty($res->json('token_type'))
				) {
					// Expiration time offset -30min
					$expires_in = now()->addSeconds($res->json('expires_in'))->subSeconds(1800);

					$this->payuToken = new PayuToken(
						$expires_in,
						$res->json('access_token'),
						$res->json('grant_type'),
						$res->json('token_type'),
						$res->json('refresh_token') ?? null,
					);
					Cache::store('file')->put($this->cacheKeyName(), $this->payuToken, $expires_in);
				} else {
					throw new Exception('Authorization invalid response', 422);
				}
			} else {
				throw new Exception($res->json('error'), $res->status());
			}
		}
	}

	/**
	 * Create payment order array
	 *
	 * @param Donation $donation
	 * @return array
	 */
	public function createOrder(Donation $donation): array
	{
		$order['merchantPosId'] = $this->merchantPosId();
		$order['currencyCode'] = $this->currencyName();
		$order['continueUrl'] = request()->getSchemeAndHttpHost() . self::SUCCESS_URL; // Customer page after successfull payment
		$order['notifyUrl'] = request()->getSchemeAndHttpHost() . self::NOTIFY_URL; // Payu notifications
		$order['customerIp'] = request()->ip();
		$order['extOrderId'] = $donation->payment_id; // Must be unique!
		$order['totalAmount'] = $donation->amount; // Integer
		$order['description'] = $donation->message; // Greetings
		// Required
		$order['products'][0]['unitPrice'] = $donation->amount;
		$order['products'][0]['name'] = 'Donate';
		$order['products'][0]['quantity'] = 1;
		// Buyer optional
		$order['buyer']['language'] = strtolower(env('PAYU_LANG', 'pl'));
		$order['buyer']['email'] = $donation->email;
		$order['buyer']['firstName'] = $donation->name;
		$order['buyer']['lastName'] = $donation->last_name ?? $donation->name;
		if (!empty($donation->phone)) {
			$order['buyer']['phone'] = $donation->phone;
		}

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
		$res = Http::withBasicAuth(env('PAYU_CLIENT_ID'), env('PAYU_CLIENT_SECRET'))
			->withoutRedirecting()->connectTimeout(10)->timeout(60)->acceptJson()
			->post($this->urlOrders(), $this->createOrder($donation));

		// Success 200 or 302
		if ($res->ok() || $res->found()) {
			// Update
			$donation->external_id = $res->json('orderId');
			$donation->url = $res->json('redirectUri');
			$donation->save();

			// extOrderId, orderId, redirectUri
			return $res->json('redirectUri');
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
		$res = Http::withBasicAuth(env('PAYU_CLIENT_ID'), env('PAYU_CLIENT_SECRET'))
			->withoutRedirecting()->connectTimeout(10)->timeout(60)->acceptJson()
			->get($this->urlOrderDetails($id));

		return $res->json();
	}

	/**
	 * Paymethods with BasicAuth
	 *
	 * @param string $id
	 * @return array
	 */
	public function paymethods(): array
	{
		$res = Http::withBasicAuth(env('PAYU_CLIENT_ID'), env('PAYU_CLIENT_SECRET'))
			->withoutRedirecting()->connectTimeout(10)->timeout(60)->acceptJson()
			->get($this->urlPaymethods());

		return $res->json();
	}

	/**
	 * Create payment order with BasicAuth
	 *
	 * @param Donation $d
	 * @return string|null Payment url or null
	 */
	public function oAuthDonate(Donation $donation): string|null
	{
		$this->authorize();

		$res = Http::withToken($this->getToken())
			->withoutRedirecting()->connectTimeout(10)->timeout(60)->acceptJson()
			->post($this->urlOrders(), $this->createOrder($donation));

		// Success 200 or 302
		if ($res->ok() || $res->found()) {
			// Update
			$donation->external_id = $res->json('orderId');
			$donation->url = $res->json('redirectUri');
			$donation->save();
			// extOrderId, orderId, redirectUri
			return $res->json('redirectUri');
		} else {
			return null;
		}
	}

	/**
	 * Order details with oAuth bearer token
	 *
	 * @param string $id
	 * @return array
	 */
	public function oAuthOrderDetails($id): array
	{
		$this->authorize();

		$res = Http::withToken($this->getToken())
			->withoutRedirecting()->connectTimeout(10)->timeout(60)->acceptJson()
			->get($this->urlOrderDetails($id));

		return $res->json();
	}

	/**
	 * Paymethods with oAuth bearer token
	 *
	 * @param string $id
	 * @return array
	 */
	public function oAuthPaymethods(): array
	{
		$this->authorize();

		$res = Http::withToken($this->getToken())
			->withoutRedirecting()->connectTimeout(10)->timeout(60)->acceptJson()
			->get($this->urlPaymethods());

		return $res->json();
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
			if (empty(env('PAYU_CLIENT_ID')) || empty(env('PAYU_CLIENT_SECRET'))) {
				throw new Exception("Empty PosId or secret", 422);
			}

			$valid = $request->validated();
			$valid['amount'] = $this->toCents($valid['amount']);
			$valid['payment_id'] = Str::uuid();
			$valid['status'] = PaymentStatusEnum::NEW->value;
			$valid['gateway'] = PaymentGatewaysEnum::PAYU->value;
			$valid['currency'] = $this->currencyName();

			$donation = Donation::create($valid);

			// Create payment link
			$pay = new PayuDonate();
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

			$data = trim(request()->getContent()); // Json

			if (empty($data)) {
				throw new Exception('Notify invalid data', 422);
			}

			if (!json_validate($data)) {
				throw new Exception("Notify invalid json", 422);
			}

			if (!$this->isNotifyVerified($data)) {
				throw new Exception('Notify invalid signature', 422);
			}

			$this->logInSandbox($data);

			$notify = $this->jsonDecode($data);

			// Confirm Payment
			if (!empty($notify->order->extOrderId)) {
				// Get local db
				$donation = Donation::where('payment_id', $notify->order->extOrderId)->first();

				if (
					$donation instanceof Donation && !empty($donation->external_id)
				) {
					// From payu
					$payment = $this->orderDetails($donation->external_id);

					if ($payment['status']['statusCode'] == self::SUCCESS) {

						$status = strtolower($payment['orders'][0]['status']);

						if ($status == 'waiting_for_confirmation') {
							$status = PaymentStatusEnum::WAITING->value;
						}

						if (
							in_array(
								$status,
								array_column(PaymentStatusEnum::cases(), 'value')
							)
						) {
							// Update donation status
							$donation->status = $status;
							$donation->save();

							return response()->json([
								'message' => 'Comfirmed'
							], 200);
						}
					}
				}
			}

			// Confirm Refund
			if (
				!empty($notify->refund) &&
				!empty($notify->extOrderId)
			) {
				// TODO: Payu refunds (not needed here) $notify->refund->refundId
				// $d = Donation::where('payment_id', $notify->extOrderId)->first();
				// if ($d instanceof Donation) {
				// 	return response()->json([
				// 		'message' => 'Comfirmed'
				// 	], 200);
				// }

				return response()->json([
					'message' => 'Comfirmed'
				], 200);
			}

			throw new Exception("Some error occurred 👻👽🤡", 422);
		} catch (Throwable $e) {
			report($e);

			return response()->json([
				'message' => 'Not comfirmed'
			], 422);
		}
	}

	/**
	 * Payu signature verification
	 *
	 * @param string $msg Json message from payu
	 * @return boolean
	 */
	public function isNotifyVerified($msg)
	{
		$header = $this->getNotifyHeader();

		if ($header == null) {
			return false;
		}

		$arr =  $this->parseNotifyHeader($header);

		if ($arr == null) {
			return false;
		}

		return $this->verifyNotifySignature(
			$msg,
			$arr['signature'],
			$this->merchantSecondKey(),
			$arr['algorithm']
		);
	}

	/**
	 * Get payu signature from header
	 *
	 * @return void
	 */
	public function getNotifyHeader(): string|null
	{
		return request()->header('OpenPayU-Signature') ?? null;
	}

	/**
	 * Function returns signature data object
	 *
	 * @param string $data
	 *
	 * @return null|array
	 */
	public function parseNotifyHeader($str): array|null
	{
		$a = [];

		if (empty($str)) {
			return null;
		}

		$l = explode(';', rtrim($str, ';'));

		if (empty($l)) {
			return null;
		}

		foreach ($l as $v) {
			$e = explode('=', $v);
			if (count($e) != 2) {
				return null;
			}
			$a[$e[0]] = $e[1];
		}

		return $a;
	}

	/**
	 * Check is signature valid
	 *
	 * @param string $message
	 * @param string $signature
	 * @param string $pos_id
	 * @param string $algorithm
	 *
	 * @return bool
	 */
	public function verifyNotifySignature($message, $signature, $secondKey, $algo = 'MD5')
	{
		// TODO: Check if flags are needed
		$message = $this->jsonEncode(json_decode($message));

		if (!empty($signature)) {
			$str = $message . $secondKey;

			if ($algo === 'MD5') {
				$hash = md5($str);
			} else if (in_array($algo, array('SHA', 'SHA1', 'SHA-1'))) {
				$hash = sha1($str);
			} else {
				$hash = hash('sha256', $str);
			}

			if (strcmp($signature, $hash) == 0) {
				return true;
			}
		}

		return false;
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
}
