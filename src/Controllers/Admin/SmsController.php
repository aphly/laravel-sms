<?php

namespace Aphly\LaravelSms\Controllers\Admin;

use Aphly\Laravel\Exceptions\ApiException;
use Aphly\Laravel\Models\Breadcrumb;

use Aphly\LaravelSms\Models\Sms;
use Aphly\LaravelSms\Models\SmsSite;
use Aphly\LaravelSms\Models\SmsTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SmsController extends Controller
{
    public $index_url = '/email_admin/email/index';
    public $p_url = '/email_admin/site/index';

    private $currArr = ['name'=>'邮件','key'=>'email'];

    public function index(Request $request)
    {
        $site_id = $request->query('site_id','');
        $res['emailSite'] = SmsSite::where('id',$site_id)->firstOrError();
        $res['search']['email'] = $request->query('email', '');
        $res['search']['string'] = http_build_query($request->query());
        $res['list'] = Sms::when($res['search'],
                            function ($query, $search) {
                                if($search['email']!==''){
                                    $query->where('email', $search['email']);
                                }
                            })
                        ->where('site_id', $site_id)
                        ->orderBy('id', 'desc')
                        ->Paginate(config('base.perPage'))->withQueryString();
        $res['breadcrumb'] = Breadcrumb::render([
            ['name'=>$this->currArr['name'].'管理','href'=>$this->p_url],
            ['name'=>$res['emailSite']->host,'href'=>$this->index_url.'?site_id='.$res['emailSite']->id],
        ]);
        return $this->makeView('laravel-sms::admin.email.index', ['res' => $res]);
    }

    public function detail(Request $request)
    {
        $site_id = $request->query('site_id','');
        $res['emailSite'] = SmsSite::where('id',$site_id)->firstOrError();
        $res['info'] = Sms::where('id',$request->query('id',0))->firstOrNew();
        $res['breadcrumb'] = Breadcrumb::render([
            ['name'=>$this->currArr['name'].'管理','href'=>$this->p_url],
            ['name'=>$res['emailSite']->host,'href'=>$this->index_url.'?site_id='.$res['emailSite']->id],
            ['name'=>'详情','href'=>'/email_admin/'.$this->currArr['key'].'/detail?id='.$res['info']->id.'&site_id='.$res['emailSite']->id]
        ]);
        return $this->makeView('laravel-sms::admin.email.detail',['res'=>$res]);
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

    public function testAliyun(Request $request)
    {
        if($request->isMethod('post')) {
            $input = $request->all();
            $arr['phone'] = $input['phone'];
            $templateInfo = SmsTemplate::where('status',1)->where('id',$input['template_id'])->with('driver')->firstOrError();
            $arr['app_id'] = $templateInfo->driver->app_id;
            $arr['app_key'] = $templateInfo->driver->app_key;
            $arr['sign_name'] = $templateInfo->sign_name;
            $arr['template_code'] = $templateInfo->template_code;
            $arr['template_param'] = str_replace('str',$input['sms_code'],$templateInfo->template_param);
            if($arr['app_id'] && $arr['app_key'] && $arr['phone'] && $arr['sign_name'] && $arr['template_code'] && $arr['template_param']){
                try{
                    Sms::main($arr);
                }catch (\Exception $err){
                    throw $err;
                }
            }else{
                throw new ApiException(['code'=>3,'msg'=>'参数错误']);
            }
        }else{
            $res['breadcrumb'] = Breadcrumb::render([
                ['name'=>$this->currArr['name'].'测试','href'=>$this->index_url]
            ]);
            $res['template'] = SmsTemplate::where('status',1)->with('driver')->get();
            return $this->makeView('laravel-sms::admin.sms.test_aliyun',['res'=>$res]);
        }
    }

    public function testLocal(Request $request)
    {
        $res['template'] = SmsTemplate::where('status',1)->with('driver')->get();
        if($request->isMethod('post')) {
            $input = $request->all();
            $input['timestamp'] = time();

        }else{
            $res['breadcrumb'] = Breadcrumb::render([
                ['name'=>$this->currArr['name'].'测试','href'=>$this->index_url]
            ]);
            return $this->makeView('laravel-sms::admin.sms.test_local',['res'=>$res]);
        }
    }
}
