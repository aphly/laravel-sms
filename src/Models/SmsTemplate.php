<?php

namespace Aphly\LaravelSms\Models;

use Aphly\Laravel\Models\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SmsTemplate extends Model
{
    use HasFactory;
    protected $table = 'sms_template';
    protected $fillable = [
        'driver_id','sign_name','template_code','status'
    ];

    public function driver()
    {
        return $this->belongsTo(SmsDriver::class,'driver_id','id');
    }

}
