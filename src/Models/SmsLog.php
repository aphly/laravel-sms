<?php

namespace Aphly\LaravelSms\Models;

use Aphly\Laravel\Exceptions\ApiException;
use Aphly\Laravel\Libs\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsLog extends Model
{
    use HasFactory;
    protected $table = 'sms_log';
    protected $primaryKey = 'ip';
    public $timestamps = false;

    public function ipLimit(){
        $time = time();
        $ip = request()->getClientIp();
        $info =  self::find($ip);
        if($info){
            if(Helper::is_today($info->lasttime)){
                if($info['times']<config('sms.iplimit')){
                    $info->times=$info->times+1;
                }else{
                    throw new ApiException(['code'=>10001,'msg'=>'每天同IP限制'.config('sms.iplimit').'条']);
                }
            }else{
                $info->times=1;
                $info->lasttime = $time;
            }
        }else{
            $info = new self;
            $info->ip=$ip;
            $info->times=1;
            $info->lasttime = $time;
        }
        return $info->save();
    }

}
