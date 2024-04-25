<?php


use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::middleware(['limit'])->group(function () {
    Route::match(['post'],'sms/send', 'Aphly\LaravelSms\Controllers\Front\SmsController@send');
    Route::match(['get'],'sms/check', 'Aphly\LaravelSms\Controllers\Front\SmsController@check');
});

Route::middleware(['web'])->group(function () {

    Route::prefix('sms_admin')->middleware(['managerAuth'])->group(function () {
        Route::middleware(['rbac'])->group(function () {
            Route::get('sms/index', 'Aphly\LaravelSms\Controllers\Admin\SmsController@index');
            Route::get('sms/detail', 'Aphly\LaravelSms\Controllers\Admin\SmsController@detail');
            Route::post('sms/del', 'Aphly\LaravelSms\Controllers\Admin\SmsController@del');

            $route_arr = [
                ['site','\SmsSiteController'],['driver','\SmsDriverController'],['template','\SmsTemplateController'],
            ];

            foreach ($route_arr as $val){
                Route::get($val[0].'/index', 'Aphly\LaravelSms\Controllers\Admin'.$val[1].'@index');
                Route::get($val[0].'/form', 'Aphly\LaravelSms\Controllers\Admin'.$val[1].'@form');
                Route::post($val[0].'/save', 'Aphly\LaravelSms\Controllers\Admin'.$val[1].'@save');
                Route::post($val[0].'/del', 'Aphly\LaravelSms\Controllers\Admin'.$val[1].'@del');
            }
            Route::match(['get','post'],'sms/test_aliyun', 'Aphly\LaravelSms\Controllers\Admin\SmsController@testAliyun');
            Route::match(['get','post'],'sms/test_local', 'Aphly\LaravelSms\Controllers\Admin\SmsController@testLocal');
        });
    });

});
