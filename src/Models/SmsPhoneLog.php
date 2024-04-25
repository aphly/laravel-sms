<?php

namespace Aphly\LaravelSms\Models;

use Aphly\Laravel\Exceptions\ApiException;
use Aphly\Laravel\Libs\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Aphly\Laravel\Models\Model;

class SmsPhoneLog extends Model
{
    use HasFactory;
    protected $table = 'sms_phone_log';
    protected $primaryKey = 'phone';
    public $incrementing = false;

    protected $fillable = [
        'phone','total','times'
    ];

    static public function phoneLimit($phone,$phone_limit){
        Sms::_check();
        $info = self::find($phone);
        if($info){
            if(Helper::is_today($info->updated_at->timestamp)){
                if($info['times']<$phone_limit){
                    $info->times=$info->times+1;
                }else{
                    throw new ApiException(['code'=>10002,'msg'=>'每天同手机号限制'.$phone_limit.'条']);
                }
            }else{
                $info->times=1;
            }
            $info->total=$info->total+1;
            $info->save();
        }else{
            self::create([
                'phone'=>$phone,
                'total'=>1,
                'times'=>1,
            ]);
        }
    }

}
