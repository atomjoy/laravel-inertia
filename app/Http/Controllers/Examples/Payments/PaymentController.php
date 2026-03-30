<?php

namespace App\Http\Controllers\Payments;

use Exception;
use App\Http\Controllers\Controller;
use App\Contracts\Payments\PaymentGatewayInterface;
use App\Payments\PaymentOrder;

class PaymentController extends Controller
{
	protected $defaultGateway = 'payu';

	protected $allowedGateways = ['payu', 'paypal', 'stripe'];

	public function process($gateway, $order)
	{
		$payment = app(PaymentGatewayInterface::class)
			->get($this->getGateway($gateway));

		if ($payment == null) {
			throw new Exception("Invalid gateway name.", 422);
		}

		$order = new PaymentOrder(); // Your order here

		$url = $payment->process($order);

		return response()->json([
			'message' => 'Payment success.',
			'gateway' => $payment->name(),
			'redirect_url' => $url
		]);
	}

	public function refund($gateway, $order)
	{
		$payment = app(PaymentGatewayInterface::class)->get($this->getGateway($gateway));

		if ($payment == null) {
			throw new Exception("Invalid gateway name.", 422);
		}

		$id = $payment->refund(123456);

		return response()->json([
			'message' => 'Refund success.',
			'gateway' => $payment->name(),
			'refund_id' => $id,
		]);
	}

	public function getGateway($gateway)
	{
		if (in_array($gateway, $this->allowedGateways)) {
			return $gateway;
		}

		return $this->defaultGateway;
	}
}
