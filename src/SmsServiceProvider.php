<?php

namespace Aphly\LaravelSms;

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
//        $this->app->singleton(SmsContracts::class,function (){
//            $sms = config('sms');
//            if($sms['driver']=='qcloud'){
//				return new Qcloud;
//            }else{
//                return new Aliyun;
//            }
//        });
//		$this->mergeConfigFrom(
//            __DIR__.'/config/sms.php', 'sms'
//        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //$this->loadMigrationsFrom(__DIR__.'/migrations');
        $this->loadViewsFrom(__DIR__.'/views', 'laravel-sms');
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
    }

//    public function provides(){
//        return [SmsContracts::class];
//    }
}
