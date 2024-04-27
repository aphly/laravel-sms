<div class="top-bar">
    <h5 class="nav-title">{!! $res['breadcrumb'] !!}</h5>
</div>
<style>
    .table_scroll .table_header li:nth-child(4),.table_scroll .table_tbody li:nth-child(4){flex: 0 0 300px;}
    .table_scroll .table_header li:nth-child(6),.table_scroll .table_tbody li:nth-child(6){flex: 0 0 200px;}
</style>
<div class="imain">
    <div class="itop ">
        <form method="get" action="/sms_admin/sms/index" class="select_form">
        <div class="search_box ">
            <input type="hidden" name="site_id"  value="{{$res['smsSite']->id}}">
            <input type="search" name="phone" placeholder="手机号码" value="{{$res['search']['phone']}}">
            <button class="" type="submit">搜索</button>
        </div>
        </form>
    </div>
    <form method="post"  @if($res['search']['string']) action="/sms_admin/sms/del?{{$res['search']['string']}}" @else action="/sms_admin/sms/del" @endif  class="del_form">
    @csrf
        <div class="table_scroll">
            <div class="table">
                <ul class="table_header">
                    <li >ID</li>
                    <li >手机号码</li>
                    <li >验证码</li>
                    <li >类型</li>
                    <li >队列通道</li>
                    <li >日期</li>
                    <li >状态</li>
                    <li >操作</li>
                </ul>
                @if($res['list']->total())
                    @foreach($res['list'] as $v)
                    <ul class="table_tbody @if($v['viewed']==1) viewed @endif">
                        <li><input type="checkbox" class="delete_box" name="delete[]" value="{{$v['id']}}">{{$v['id']}}</li>
                        <li class="wenzi">{{$v['phone']}}</li>
                        <li>
                            {{$v['sms_code']}}
                        </li>
                        <li>
                            @if($dict['sms_type'])
                                @if($v->type==1)
                                    <span class="badge badge-success">{{$dict['sms_type'][$v->type]}}</span>
                                @else
                                    <span class="badge badge-secondary">{{$dict['sms_type'][$v->type]}}</span>
                                @endif
                            @endif
                        </li>
                        <li>
                            @if($dict['sms_queue_priority'])
                                @if($v->type==1)
                                    <span class="badge badge-success">{{$dict['sms_queue_priority'][$v->queue_priority]}}</span>
                                @else
                                    <span class="badge badge-secondary">{{$dict['sms_queue_priority'][$v->queue_priority]}}</span>
                                @endif
                            @endif
                        </li>
                        <li>
                            {{$v->created_at}}
                        </li>
                        <li>
                            @if($dict['sms_status'])
                                @if($v->status==1)
                                    <span class="badge badge-success">{{$dict['sms_status'][$v->status]}}</span>
                                @else
                                    <span class="badge badge-secondary">{{$dict['sms_status'][$v->status]}}</span>
                                @endif
                            @endif
                        </li>
                        <li>
                            <a class="badge badge-info ajax_html" data-href="/sms_admin/sms/detail?id={{$v['id']}}&site_id={{$res['smsSite']->id}}">查看</a>
                        </li>
                    </ul>
                    @endforeach
                    <ul class="table_bottom">
                        <li>
                            <input type="checkbox" class="delete_box deleteboxall"  onclick="checkAll(this)">
                            <button class="badge badge-danger del" type="submit">删除</button>
                        </li>
                        <li>
                            {{$res['list']->links('laravel::admin.pagination')}}
                        </li>
                    </ul>
                @endif
            </div>
        </div>

    </form>
</div>


