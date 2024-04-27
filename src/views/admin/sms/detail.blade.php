
<div class="top-bar">
    <h5 class="nav-title">{!! $res['breadcrumb'] !!}</h5>
</div>

<div class="imain">
    <div class="">
        <div>
            <ul class="detail_view">
                <li><div class="view_li_l">Host</div><div class="view_li_r">{{$res['smsSite']->host}}</div></li>
                <li><div class="view_li_l">手机号码</div><div class="view_li_r">{{$res['info']->phone}}</div></li>
                <li><div class="view_li_l">验证码</div><div class="view_li_r">{{$res['info']->sms_code}}</div></li>
                <li><div class="view_li_l">有效期</div><div class="view_li_r">{{$res['info']->expire_at}}</div></li>
                <li><div class="view_li_l">类型</div><div class="view_li_r">
                        @if($dict['sms_type'])
                            {{$dict['sms_type'][$res['info']->type]}}
                        @endif
                    </div></li>
                <li><div class="view_li_l">队列通道</div><div class="view_li_r">
                        @if($dict['sms_queue_priority'])
                            {{$dict['sms_queue_priority'][$res['info']->queue_priority]}}
                        @endif
                    </div></li>
                <li><div class="view_li_l">响应</div><div class="view_li_r">{{$res['info']->res}}</div></li>
                <li><div class="view_li_l">状态</div><div class="view_li_r">
                        @if($dict['sms_status'])
                            {{$dict['sms_status'][$res['info']->status]}}
                        @endif</div></li>
                <li><div class="view_li_l">创建时间</div><div class="view_li_r">{{$res['info']->created_at}}</div></li>
                <li><div class="view_li_l">更新时间</div><div class="view_li_r">{{$res['info']->created_at}}</div></li>
            </ul>
        </div>
    </div>

</div>
<style>

</style>
<script>

</script>
