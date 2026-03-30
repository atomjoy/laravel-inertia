<?php

namespace App\Contracts\Payments;

interface PaymentGatewayInterface
{
	/**
	 * Gateway name
	 *
	 * @return string
	 */
	public function name(): string;

	/**
	 * Proccess payment here with order amount.
	 *
	 * @param PaymentOrderInterface $order Order object
	 * @return string $url Redirect url
	 */
	public function process(PaymentOrderInterface $order): string;

	/**
	 * Proccess refund here with transaction id.
	 *
	 * @param string $id Transaction id
	 * @return string $id Refund id
	 */
	public function refund(string $id): string;
}
