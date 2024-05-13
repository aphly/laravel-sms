<?php

namespace Aphly\LaravelSms\Controllers\Front;

use Aphly\Laravel\Exceptions\ApiException;
use Aphly\Laravel\Libs\Verifier;
use Aphly\LaravelSms\Models\Sms;
use Aphly\LaravelSms\Models\SmsDriver;
use Aphly\LaravelSms\Models\SmsIpLog;
use Aphly\LaravelSms\Models\SmsPhoneLog;
use Aphly\LaravelSms\Models\SmsSite;
use Aphly\LaravelSms\Models\SmsTemplate;
use Illuminate\Http\Request;

class SmsController extends Controller
{
    public function send(Request $request)
    {
        if($request->isMethod('post')) {
            list($input,$smsSite,$smsTemplate,$smsDriver) = $this->_check($request);

            $now = time();
            $input['site_id'] = $smsSite->id;
            $input['expire_at'] = $now+$smsSite->expire*60;
            $input['created_at'] = $now;
            $input['type'] = $smsSite->type?1:0;
            $input['queue_priority'] = ($input['queue_priority']??0)?1:0;
            $sms = Sms::create($input);
            if($sms->id){
                $template_param = $sms->driverSmscode($smsDriver,$sms->sms_code);
                $sms->send([
                    'id'=>$sms->id,
                    'driver'=>$smsDriver,
                    'smsSite_id'=>$smsSite->id,
                    'key_id'=>$smsDriver->key_id,
                    'key_secret'=>$smsDriver->key_secret,
                    'phone'=> $sms->phone,
                    'template_param'=>$template_param,
                    'sign_name'=>$smsTemplate->sign_name,
                    'template_code'=>$smsTemplate->template_code,
                    'type'=>$smsSite->type,
                    'queue_priority'=>$input['queue_priority'],
                ]);
                throw new ApiException(['code'=>0,'msg'=>'success']);
            }else{
                throw new ApiException(['code'=>5,'msg'=>'error']);
            }
        }else{
            throw new ApiException(['code'=>6,'msg'=>'Post fail']);
        }
    }

    public function _check($request,$is_check=false)
    {
        Sms::clearOverDays();
        $input = $request->all();
        Verifier::handle($input,[
            'phone'=>'required|digits:11',
            'sms_code'=>'required|digits_between:4,6',
            'app_id'=>'required',
            'sign'=>'required',
            'timestamp'=>'required',
        ],[
            'phone.required'=>'手机号码缺少',
            'phone.digits'=>'手机号码格式错误',
            'sms_code.required'=>'验证码缺少',
            'sms_code.digits_between'=>'验证码格式错误',
            'app_id.required'=>'App_id缺少',
            'sign.required'=>'签名缺少',
        ]);
        $smsSite = SmsSite::where('app_id',$input['app_id'])->statusOrError();
        $smsTemplate = SmsTemplate::where('id',$smsSite->template_id)->statusOrError();
        $smsDriver = SmsDriver::where('id',$smsTemplate->driver_id)->statusOrError();
        if(!$is_check){
            if($smsSite->total_num!==0){
                if($smsSite->total_num<=$smsSite->used_num){
                    throw new ApiException(['code'=>7,'msg'=>'请联系管理员']);
                }
            }
            SmsIpLog::ipLimit($smsSite->ip_limit);
            SmsPhoneLog::phoneLimit($input['phone'],$smsSite->phone_limit);
        }
        if(\Aphly\Laravel\Libs\Sms::sign($input,$smsSite->app_key)!=$input['sign']){
            throw new ApiException(['code'=>4,'msg'=>'sign error']);
        }
        return [$input,$smsSite,$smsTemplate,$smsDriver];
    }

    public function check(Request $request)
    {
        if($request->isMethod('post')) {
            list($arr) = $this->_check($request,true);
            $info = Sms::where('phone',$arr['phone'])->orderBy('created_at','desc')->statusOrError();
            if($info->sms_code==$arr['sms_code']){
                if($info->expire_at>time()){
                    throw new ApiException(['code'=>0,'msg'=>'验证通过']);
                }else{
                    throw new ApiException(['code'=>2,'msg'=>'验证码过期']);
                }
            }else{
                throw new ApiException(['code'=>1,'msg'=>'无效验证码']);
            }
        }else{
            throw new ApiException(['code'=>6,'msg'=>'Post fail']);
        }
    }

}
