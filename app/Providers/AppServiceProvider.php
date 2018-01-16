<?php

namespace App\Providers;

use App\Utils\AppConstant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Laravel\Horizon\Horizon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
	    Schema::defaultStringLength(191);
	    Horizon::auth(function ($request) {
		    $auth = Auth::guard(AppConstant::GUARD_ADMIN);
		    if ($auth->check()) {
			    return true;
		    }
	    });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
