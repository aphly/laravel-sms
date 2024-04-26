<?php

namespace Aphly\LaravelSms\Models;

use Aphly\Laravel\Models\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SmsSite extends Model
{
    use HasFactory;
    protected $table = 'sms_site';
    //public $timestamps = false;
    protected $fillable = [
        'app_id','app_key','host','status','template_id','type','ip_limit','phone_limit','expire'
    ];

    function template()
    {
        return $this->belongsTo(SmsTemplate::class,'template_id','id');
    }

}
