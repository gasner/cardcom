<?php

namespace App\Providers;

use Cardcom\Setting;
use Illuminate\Support\ServiceProvider;

class CardcomServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->publishes([
			__DIR__ . '/laravel/config/cardcom.php' => config_path('cardcom.php'),
		]);
		$this->mergeConfigFrom(
			__DIR__ . '/laravel/config/cardcom.php', 'cardcom'
		);
	}

	/**
	 * Register services.
	 *
	 * @return void
	 */
	public function register()
	{
		Setting::setTerminal(config("cardcom.terminal"));
		Setting::setUser(config("cardcom.user"));
	}
}
