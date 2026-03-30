<?php

namespace App\Providers;

use App\Contracts\Payments\PaymentGatewayInterface;
use App\Payments\Gateways\PayuGateway;
use App\Payments\Gateways\PayPalGateway;
use App\Payments\Gateways\StripeGateway;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;

class PaymentGatewayProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 */
	public function register(): void
	{
		// Payment gateways collection
		$this->app->bind(
			PaymentGatewayInterface::class,
			function ($app) {
				return collect([
					'payu' => app(PayuGateway::class),
					'paypal' => app(PayPalGateway::class),
					'stripe' => app(StripeGateway::class),
				]);
			}
		);

		// Disable csrf_token for notifications
		ValidateCsrfToken::except([
			'/api/notify/payu/donate',
			'/notify/payu/*',
			'/notify/paypal/*',
			'/notify/stripe/*',
		]);
	}

	/**
	 * Bootstrap any application services.
	 */
	public function boot(): void
	{
		//
	}
}
