<div class="top-bar">
    <h5 class="nav-title">{!! $res['breadcrumb'] !!}</h5>
</div>
<style>
    .table_scroll .table_header li:nth-child(3),.table_scroll .table_tbody li:nth-child(3){flex: 0 0 300px;}
</style>
<div class="imain">
    <div class="itop ">
        <form method="get" action="/sms_admin/site/index" class="select_form">
        <div class="search_box ">
            <input type="search" name="name" placeholder="name" value="{{$res['search']['name']}}">
            <button class="" type="submit">搜索</button>
        </div>
        </form>
        <div class="">
            <a class="badge badge-primary ajax_html show_all0_btn" data-href="/sms_admin/site/form">添加</a>
        </div>
    </div>

    <form method="post"  @if($res['search']['string']) action="/sms_admin/site/del?{{$res['search']['string']}}" @else action="/sms_admin/site/del" @endif  class="del_form">
    @csrf
        <div class="table_scroll">
            <div class="table">
                <ul class="table_header">
                    <li >ID</li>
                    <li >Appid</li>
                    <li >Host</li>
                    <li >总条数</li>
                    <li >已发条数</li>
                    <li >status</li>
                    <li >操作</li>
                </ul>
                @if($res['list']->total())
                    @foreach($res['list'] as $v)
                    <ul class="table_tbody">
                        <li><input type="checkbox" class="delete_box" name="delete[]" value="{{$v['id']}}">{{$v['id']}}</li>
                        <li>{{ $v['app_id'] }}</li>
                        <li>{{ $v['host'] }}</li>
                        <li>{{ $v['total_num'] }}</li>
                        <li>{{ $v['used_num'] }}</li>
                        <li>
                            @if($dict['status'])
                                @if($v['status']==1)
                                    <span class="badge badge-success">{{$dict['status'][$v['status']]}}</span>
                                @else
                                    <span class="badge badge-secondary">{{$dict['status'][$v['status']]}}</span>
                                @endif
                            @endif
                        </li>
                        <li>
                            <a class="badge badge-info ajax_html" data-href="/sms_admin/site/form?id={{$v['id']}}">编辑</a>
                            <a class="badge badge-info ajax_html" data-href="/sms_admin/sms/test?site_id={{$v['id']}}">测试</a>
                            <a class="badge badge-info ajax_html" data-href="/sms_admin/sms/index?site_id={{$v['id']}}">短信记录</a>
                        </li>
                    </ul>
                    @endforeach
                    <ul class="table_bottom">
                        <li>
                            <input type="checkbox" class="delete_box deleteboxall"  onclick="checkAll(this)">
                            <button class="badge badge-danger del" type="submit">删除</button>
                        </li>
                        <li >
                            {{$res['list']->links('laravel::admin.pagination')}}
                        </li>
                    </ul>
                @endif
            </div>
        </div>

    </form>
</div>


