
<div class="top-bar">
    <h5 class="nav-title">{!! $res['breadcrumb'] !!}</h5>
</div>

<div class="imain">
    <div class="">
        <div>
            <ul class="email">
                <li><span>email</span><span>{{$res['info']->email}}</span></li>
                <li><span>site_id</span><span>{{$res['emailSite']->host}}</span></li>
                <li><span>type</span><span>
                        @if($dict['email_type'])
                            {{$dict['email_type'][$res['info']->type]}}
                        @endif
                    </span></li>
                <li><span>queue_priority</span><span>
                        @if($dict['email_queue_priority'])
                            {{$dict['email_queue_priority'][$res['info']->queue_priority]}}
                        @endif
                    </span></li>
                <li><span>is_cc</span><span>
                        @if($dict['yes_no'])
                            {{$dict['yes_no'][$res['info']->is_cc]}}
                        @endif
                    </span></li>
                <li><span>status</span><span>
                        @if($dict['email_status'])
                           {{$dict['email_status'][$res['info']->status]}}
                        @endif</span></li>
                <li><span>created_at</span><span>{{$res['info']->created_at}}</span></li>
                <li><span>title</span><span>{{$res['info']->title}}</span></li>
                <li><span>content</span><span style="word-break: break-word;">{{$res['info']->content}}</span></li>
            </ul>
        </div>
    </div>

</div>
<style>
    .email{}
    .email li{line-height:30px;display:flex}
    .email li span:first-child{margin-right:20px;width:100px;text-align:right;color:#666}
    .email li span:last-child{font-weight:600}
</style>
<script>

</script>
