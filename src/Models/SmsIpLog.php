<?php

namespace Aphly\LaravelSms\Models;

use Aphly\Laravel\Exceptions\ApiException;
use Aphly\Laravel\Libs\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Aphly\Laravel\Models\Model;

class SmsIpLog extends Model
{
    use HasFactory;
    protected $table = 'sms_ip_log';
    protected $primaryKey = 'ip';
    public $incrementing = false;

    protected $fillable = [
        'ip','times'
    ];

    static public function ipLimit($ip_limit){
        $ip = request()->getClientIp();
        $info = self::find($ip);
        if($info){
            if(Helper::is_today($info->updated_at->timestamp)){
                if($info['times']<$ip_limit){
                    $info->times=$info->times+1;
                }else{
                    throw new ApiException(['code'=>10001,'msg'=>'每天同IP限制'.$ip_limit.'条']);
                }
            }else{
                $info->times=1;
            }
            return $info->save();
        }else{
            return self::create(['ip'=>$ip,'times'=>1]);
        }
    }

}
