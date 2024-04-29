<?php

namespace Aphly\LaravelSms\Controllers\Admin;

use Aphly\Laravel\Exceptions\ApiException;
use Aphly\Laravel\Libs\Verifier;
use Aphly\Laravel\Models\Breadcrumb;

use Aphly\LaravelSms\Models\Sms;
use Aphly\LaravelSms\Models\SmsDriver;
use Aphly\LaravelSms\Models\SmsSite;
use Aphly\LaravelSms\Models\SmsTemplate;
use Illuminate\Http\Request;

class SmsController extends Controller
{
    public $index_url = '/sms_admin/sms/index';

    public $p_url = '/sms_admin/site/index';

    private $currArr = ['name'=>'SMS','key'=>'sms'];

    public function index(Request $request)
    {
        $site_id = $request->query('site_id','');
        $res['smsSite'] = SmsSite::where('id',$site_id)->firstOrError();
        $res['search']['phone'] = $request->query('phone', '');
        $res['search']['string'] = http_build_query($request->query());
        $res['list'] = Sms::when($res['search'],
                            function ($query, $search) {
                                if($search['phone']!==''){
                                    $query->where('phone', $search['phone']);
                                }
                            })
                        ->where('site_id', $site_id)
                        ->orderBy('id', 'desc')
                        ->Paginate(config('base.perPage'))->withQueryString();
        $res['breadcrumb'] = Breadcrumb::render([
            ['name'=>'站点管理','href'=>$this->p_url],
            ['name'=>$res['smsSite']->host,'href'=>$this->index_url.'?site_id='.$res['smsSite']->id],
        ]);
        return $this->makeView('laravel-sms::admin.sms.index', ['res' => $res]);
    }

    public function detail(Request $request)
    {
        $res['smsSite'] = SmsSite::where('id',$request->query('site_id',0))->firstOrError();
        $res['info'] = Sms::where('id',$request->query('id',0))->firstOrError();
        $res['breadcrumb'] = Breadcrumb::render([
            ['name'=>$this->currArr['name'].'管理','href'=>$this->p_url],
            ['name'=>$res['smsSite']->host,'href'=>$this->index_url.'?site_id='.$res['smsSite']->id],
            ['name'=>'详情','href'=>'/sms_admin/'.$this->currArr['key'].'/detail?id='.$res['info']->id.'&site_id='.$res['smsSite']->id]
        ]);
        return $this->makeView('laravel-sms::admin.sms.detail',['res'=>$res]);
    }

    public function del(Request $request)
    {
        $query = $request->query();
        $redirect = $query?$this->index_url.'?'.http_build_query($query):$this->index_url;
        $post = $request->input('delete');
        if(!empty($post)){
            Sms::whereIn('id',$post)->delete();
            throw new ApiException(['code'=>0,'msg'=>'操作成功','data'=>['redirect'=>$redirect]]);
        }
    }

    public function driver(Request $request)
    {
        if($request->isMethod('post')) {
            $input = $request->all();
            Verifier::handle($input,[
                'template_id'=>'required',
                'phone'=>'required',
                'sms_code'=>'required'
            ]);
            $arr['phone'] = $input['phone'];
            $templateInfo = SmsTemplate::where('status',1)->where('id',$input['template_id'])->with('driver')->firstOrError();
            $arr['key_id'] = $templateInfo->driver->key_id;
            $arr['key_secret'] = $templateInfo->driver->key_secret;
            $arr['sign_name'] = $templateInfo->sign_name;
            $arr['template_code'] = $templateInfo->template_code;
            $arr['template_param'] = '{"code":"'.$input['sms_code'].'"}';
            if($arr['key_id'] && $arr['key_secret'] && $arr['phone'] && $arr['sign_name'] && $arr['template_code'] && $arr['template_param']){
                Sms::main($arr,true);
            }else{
                throw new ApiException(['code'=>3,'msg'=>'参数错误','data'=>$arr]);
            }
        }else{
            $res['breadcrumb'] = Breadcrumb::render([
                ['name'=>'通道测试','href'=>'']
            ]);
            $res['template'] = SmsTemplate::where('status',1)->get()->groupBy('driver_id');
            $res['driver'] = SmsDriver::where('status',1)->get()->keyBy('id')->toArray();
            return $this->makeView('laravel-sms::admin.sms.driver',['res'=>$res]);
        }
    }

    public function test(Request $request)
    {
        $input = $request->all();
        Verifier::handle($input,[
            'site_id'=>'required',
            'phone'=>'required',
            'sms_code'=>'required'
        ]);
        $res['smsSite'] = SmsSite::where('id',$input['site_id'])->with(['template'=>['driver']])->firstOrError();
        if($request->isMethod('post')) {
            $input['site_id'] = $res['smsSite']->id;
            $input['expire_at'] = time()+$res['smsSite']->expire*60;
            $input['type'] = $res['smsSite']->type?1:0;
            $input['queue_priority'] = ($input['queue_priority']??0)?1:0;
            $sms = Sms::create($input);
            if($sms->id){
                $sms->send([
                    'id'=>$sms->id,
                    'key_id'=>$res['smsSite']->template->driver->key_id,
                    'key_secret'=>$res['smsSite']->template->driver->key_secret,
                    'phone'=> $sms->phone,
                    'template_param'=>'{"code":"'.$sms->sms_code.'"}',
                    'sign_name'=>$res['smsSite']->template->sign_name,
                    'template_code'=>$res['smsSite']->template->template_code,
                    'type'=>$res['smsSite']->type,
                    'queue_priority'=>$input['queue_priority'],
                ]);
                throw new ApiException(['code'=>0,'msg'=>'success']);
            }else{
                throw new ApiException(['code'=>5,'msg'=>'error']);
            }
        }else{
            $res['breadcrumb'] = Breadcrumb::render([
                ['name'=>'站点管理','href'=>$this->p_url],
                ['name'=>$res['smsSite']->host.'测试','href'=>'']
            ]);
            return $this->makeView('laravel-sms::admin.sms.test',['res'=>$res]);
        }
    }
}
