<?php

namespace Aphly\LaravelSms\Controllers\Admin;


class Controller extends \Aphly\Laravel\Controllers\Admin\Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            return $next($request);
        });
        parent::__construct();
    }


}
