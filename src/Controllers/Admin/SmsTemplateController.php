<?php

namespace Aphly\LaravelSms\Controllers\Admin;

use Aphly\Laravel\Exceptions\ApiException;
use Aphly\Laravel\Libs\Verifier;
use Aphly\Laravel\Models\Breadcrumb;

use Aphly\LaravelSms\Models\SmsDriver;
use Aphly\LaravelSms\Models\SmsTemplate;
use Illuminate\Http\Request;

class SmsTemplateController extends Controller
{
    public $index_url='/sms_admin/template/index';

    private $currArr = ['name'=>'模板','key'=>'template'];

    public function index(Request $request)
    {
        $res['search']['string'] = http_build_query($request->query());
        $res['list'] = SmsTemplate::with('driver')->orderBy('id','desc')
            ->Paginate(config('base.perPage'))->withQueryString();
        $res['breadcrumb'] = Breadcrumb::render([
            ['name'=>$this->currArr['name'].'管理','href'=>$this->index_url]
        ]);
        return $this->makeView('laravel-sms::admin.template.index',['res'=>$res]);
    }

    public function form(Request $request)
    {
        $res['info'] = SmsTemplate::where('id',$request->query('id',0))->with('driver')->firstOrNew();
        $res['driver'] = SmsDriver::where('status',1)->get();
        if($res['info']->id){
            $res['breadcrumb'] = Breadcrumb::render([
                ['name'=>$this->currArr['name'].'管理','href'=>$this->index_url],
                ['name'=>'编辑','href'=>'/sms_admin/'.$this->currArr['key'].'/form?id='.$res['info']->id]
            ]);
        }else{
            $res['breadcrumb'] = Breadcrumb::render([
                ['name'=>$this->currArr['name'].'管理','href'=>$this->index_url],
                ['name'=>'新增','href'=>'/sms_admin/'.$this->currArr['key'].'/form']
            ]);
        }
        return $this->makeView('laravel-sms::admin.template.form',['res'=>$res]);
    }

    public function save(Request $request){
        $input = $request->all();
        Verifier::handle($input,[
            'driver_id'=>'required',
            'sign_name'=>'required',
            'template_code'=>'required',
            'status'=>'required'
        ]);
        SmsTemplate::updateOrCreate(['id'=>$request->query('id',0)],$input);
        throw new ApiException(['code'=>0,'msg'=>'success','data'=>['redirect'=>$this->index_url]]);
    }

    public function del(Request $request)
    {
        $query = $request->query();
        $redirect = $query?$this->index_url.'?'.http_build_query($query):$this->index_url;
        $post = $request->input('delete');
        if(!empty($post)){
            SmsTemplate::whereIn('id',$post)->delete();
            throw new ApiException(['code'=>0,'msg'=>'操作成功','data'=>['redirect'=>$redirect]]);
        }
    }

}
