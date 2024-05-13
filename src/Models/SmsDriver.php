<?php

namespace Aphly\LaravelSms\Models;

use Aphly\Laravel\Models\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SmsDriver extends Model
{
    use HasFactory;
    protected $table = 'sms_driver';
    protected $fillable = [
        'name','key_id','key_secret','status','sdk_app_id','sdk_app_key','type'
    ];

    function template()
    {
        $this->hasMany(SmsTemplate::class,'driver_id','id');
    }

}
