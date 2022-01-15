<?php

namespace Aphly\LaravelSms\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsLog extends Model
{
    use HasFactory;
    protected $table = 'sms_log';
    protected $primaryKey = 'ip';
    public $timestamps = false;
}
