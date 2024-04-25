<?php

namespace Aphly\LaravelSms\Controllers\Front;

use Aphly\Laravel\Exceptions\ApiException;
use Aphly\Laravel\Libs\Verifier;
use Aphly\LaravelSms\Models\Sms;
use Aphly\LaravelSms\Models\SmsIpLog;
use Aphly\LaravelSms\Models\SmsPhoneLog;
use Aphly\LaravelSms\Models\SmsSite;
use Illuminate\Http\Request;

class SmsController extends Controller
{
    public function send(Request $request)
    {
        if($request->isMethod('post')) {
            $input = $request->all();
            Verifier::handle($input,[
                'phone'=>'required',
                'sms_code'=>'required',
                'app_id'=>'required',
                'sign'=>'required',
                'timestamp'=>'required',
            ],[
                'phone.required'=>'手机号码缺少',
                'sms_code.required'=>'验证码缺少',
                'app_id.required'=>'App_id缺少',
                'sign.required'=>'签名缺少',
            ]);
            $smsSite = SmsSite::where('app_id',$input['app_id'])->where('status',1)->with(['template'=>['driver']])->firstOrError();
            SmsIpLog::ipLimit($smsSite->ip_limit);
            SmsPhoneLog::phoneLimit($input['phone'],$smsSite->phone_limit);
            if($this->sign($input,$smsSite->app_key)==$input['sign']){
                $now = time();
                $input['site_id'] = $smsSite->id;
                $input['expire_at'] = $now+$smsSite->expire*60;
                $input['created_at'] = $now;
                $sms = Sms::create($input);
                if($sms->id){
                    (new Sms)->send([
                        'id'=>$sms->id,
                        'key_id'=>$smsSite->template->driver->key_id,
                        'key_secret'=>$smsSite->template->driver->key_secret,
                        'phone'=> $sms->phone,
                        'template_param'=>'{"code":"'.$sms->sms_code.'"}',
                        'sign_name'=>$smsSite->template->sign_name,
                        'template_code'=>$smsSite->template->template_code,
                        'type'=>$smsSite->type,
                    ]);
                    throw new ApiException(['code'=>0,'msg'=>'success']);
                }else{
                    throw new ApiException(['code'=>5,'msg'=>'error']);
                }
            }else{
                throw new ApiException(['code'=>4,'msg'=>'sign error']);
            }
        }else{
            throw new ApiException(['code'=>6,'msg'=>'fail']);
        }
    }

    function sign($input,$app_key){
        return md5(md5($input['app_id'].$input['phone'].$input['sms_code'].$app_key).$input['timestamp']);
    }

    public function check(Request $request)
    {
        Sms::check();
    }
}
