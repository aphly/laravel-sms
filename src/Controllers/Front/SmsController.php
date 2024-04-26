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
            list($input,$smsSite) = $this->_check($request);
            $now = time();
            $input['site_id'] = $smsSite->id;
            $input['expire_at'] = $now+$smsSite->expire*60;
            $input['created_at'] = $now;
            $input['type'] = $smsSite->type?1:0;
            $input['queue_priority'] = ($input['queue_priority']??0)?1:0;
            $sms = Sms::create($input);
            if($sms->id){
                $sms->send([
                    'id'=>$sms->id,
                    'key_id'=>$smsSite->template->driver->key_id,
                    'key_secret'=>$smsSite->template->driver->key_secret,
                    'phone'=> $sms->phone,
                    'template_param'=>'{"code":"'.$sms->sms_code.'"}',
                    'sign_name'=>$smsSite->template->sign_name,
                    'template_code'=>$smsSite->template->template_code,
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

    function sign($input,$app_key){
        return md5(md5($input['app_id'].$input['phone'].$input['sms_code'].$app_key).$input['timestamp']);
    }

    public function _check($request)
    {
        Sms::clearOverDays();
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
        if($this->sign($input,$smsSite->app_key)!=$input['sign']){
            throw new ApiException(['code'=>4,'msg'=>'sign error']);
        }
        return [$input,$smsSite];
    }

    public function check(Request $request)
    {
        if($request->isMethod('post')) {
            list($arr) = $this->_check($request);
            $info = Sms::where('phone',$arr['phone'])->orderBy('created_at','desc')->firstOrError();
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
