<?php

namespace App\Http\Controllers\Stripe;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Http\Controllers\Controller;

class StripeController extends Controller
{
	public function index()
	{
		return view('payment');
	}

	public function checkout(Request $request)
	{
		Stripe::setApiKey(env('STRIPE_SECRET'));

		try {
			$session = Session::create([
				'payment_method_types' => ['card', 'blik'],
				'line_items' => [[
					'price_data' => [
						'currency' => 'usd',
						'product_data' => [
							'name' => 'Laravel Stripe Payment',
						],
						'unit_amount' => 1000, // $10.00 in cents
					],
					'quantity' => 1,
				]],
				'mode' => 'payment',
				'success_url' => route('payment.success') . '?session_id={CHECKOUT_SESSION_ID}',
				'cancel_url' => route('payment.cancel'),
				// Manual confirmation and status requires_capture (cards only),
				// 'payment_intent_data' => [
				// 	'capture_method' => 'manual',
				// ],
				// Details
				'payment_intent_data' => [
					'metadata' => [
						'external_id' => 123456,
						'email' => 'email@example.com',
						'addons' => json_encode([
							'product_0' => [
								['name' => 'Pineapple', 'price' => '1.00', 'size' => 'S'],
								['name' => 'Cheese', 'price' => '1.50', 'size' => 'S'],
							],
						]),
					],
				],
				'custom_text' => [
					'submit' => [
						// 'message' => 'The amount includes VAT ' . $vat_amount . strtoupper(config('cashier.currency')),
					],
					'after_submit' => [
						// 'message' => 'Have a nice day!',
					]
				],
			]);

			return redirect($session->url, 303);
		} catch (\Exception $e) {
			return back()->withErrors(['error' => 'Unable to create payment session: ' . $e->getMessage()]);
		}
	}
}
