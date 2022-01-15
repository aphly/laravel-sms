<?php

namespace Aphly\LaravelSms;

use Aphly\LaravelSms\Contracts\SmsContracts;
use Aphly\LaravelSms\Drivers\Ali;
use Aphly\LaravelSms\Drivers\T;
use Illuminate\Support\ServiceProvider;

class SmsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    protected $defer = true;

    public function register()
    {
        $this->app->singleton(SmsContracts::class,function (){
            $sms = config('sms');
            if($sms['driver']=='ali'){
                return new Ali;
            }else{
                return new T;
            }
        });
		$this->mergeConfigFrom(
            __DIR__.'/config/sms.php', 'sms'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
        $this->publishes([
            __DIR__.'/config/sms.php' => config_path('sms.php'),
        ]);
        $this->loadMigrationsFrom(__DIR__.'/migrations');
    }

    public function provides(){
        return [SmsContracts::class];
    }
}
