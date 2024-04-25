<?php

namespace Aphly\LaravelSms\Models;

use Aphly\Laravel\Models\Dict;
use Aphly\Laravel\Models\Manager;
use Aphly\Laravel\Models\Menu;
use Aphly\Laravel\Models\Module as Module_base;
use Illuminate\Support\Facades\DB;

class Module extends Module_base
{
    public $dir = __DIR__;

    function remoteInstall($module_id)
    {
        $manager = Manager::where('username','admin')->firstOrError();
        $menu = Menu::create(['name' => 'SMS','route' =>'','pid'=>0,'uuid'=>$manager->uuid,'type'=>1,'module_id'=>$module_id,'sort'=>10]);
        if($menu->id){
            $data=[];
            $data[] =['name' => 'SMS测试','route' =>'sms_admin/sms/test','pid'=>$menu->id,'uuid'=>$manager->uuid,'type'=>2,'module_id'=>$module_id,'sort'=>0];
            $data[] =['name' => '站点管理','route' =>'sms_admin/site/index','pid'=>$menu->id,'uuid'=>$manager->uuid,'type'=>2,'module_id'=>$module_id,'sort'=>0];
            $data[] =['name' => '通道管理','route' =>'sms_admin/driver/index','pid'=>$menu->id,'uuid'=>$manager->uuid,'type'=>2,'module_id'=>$module_id,'sort'=>0];
            $data[] =['name' => '模板管理','route' =>'sms_admin/template/index','pid'=>$menu->id,'uuid'=>$manager->uuid,'type'=>2,'module_id'=>$module_id,'sort'=>0];
            DB::table('admin_menu')->insert($data);
        }
        $menuData = Menu::where(['module_id'=>$module_id])->get();
        $data=[];
        foreach ($menuData as $val){
            $data[] =['role_id' => 1,'menu_id'=>$val->id];
        }
        DB::table('admin_role_menu')->insert($data);

        $dict = Dict::create(['name' => 'SMS状态','uuid'=>$manager->uuid,'key'=>'sms_status','module_id'=>$module_id]);
        if($dict->id){
            $data=[];
            $data[] =['dict_id' => $dict->id,'name'=>'未发送','value'=>'0'];
            $data[] =['dict_id' => $dict->id,'name'=>'已发送','value'=>'1'];
            DB::table('admin_dict_value')->insert($data);
        }

        $dict = Dict::create(['name' => 'SMS类型','uuid'=>$manager->uuid,'key'=>'sms_type','module_id'=>$module_id]);
        if($dict->id){
            $data=[];
            $data[] =['dict_id' => $dict->id,'name'=>'同步','value'=>'0'];
            $data[] =['dict_id' => $dict->id,'name'=>'队列','value'=>'1'];
            DB::table('admin_dict_value')->insert($data);
        }

        $dict = Dict::create(['name' => '队列通道','uuid'=>$manager->uuid,'key'=>'sms_queue_priority','module_id'=>$module_id]);
        if($dict->id){
            $data=[];
            $data[] =['dict_id' => $dict->id,'name'=>'普通','value'=>'0'];
            $data[] =['dict_id' => $dict->id,'name'=>'vip','value'=>'1'];
            DB::table('admin_dict_value')->insert($data);
        }
    }

    function remoteUninstall($module_id)
    {

    }



}
