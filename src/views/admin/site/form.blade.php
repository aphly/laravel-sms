
<div class="top-bar">
    <h5 class="nav-title">{!! $res['breadcrumb'] !!}</h5>
</div>
<div class="imain">
    <form method="post" @if($res['info']->id) action="/sms_admin/site/save?id={{$res['info']->id}}" @else action="/sms_admin/site/save" @endif class="save_form">
        @csrf
        <div class="">
            <div class="form-group">
                <label for="">Host</label>
                <input type="text" name="host" class="form-control " value="{{$res['info']->host}}">
                <div class="invalid-feedback"></div>
            </div>
            @if($res['info']->id)
            <div class="form-group">
                <label for="">Appid</label>
                <input type="text" name="app_id" class="form-control " value="{{$res['info']->app_id}}">
                <div class="invalid-feedback"></div>
            </div>
            <div class="form-group">
                <label for="">Appkey</label>
                <input type="text" name="app_key" class="form-control " value="{{$res['info']->app_key}}">
                <div class="invalid-feedback"></div>
            </div>
            @endif
            <div class="form-group">
                <label for="">签名 - 模板Code</label>
                <select name="template_id"  class="form-control">
                    @foreach($res['template'] as $key=>$val)
                    <optgroup label="{{$dict['driver_type'][$res['driver'][$key]['type']]}} {{$res['driver'][$key]['name']}}">
                    @foreach($val as $k=>$v)
                        <option value="{{$v->id}}" @if($res['info']->template_id==$v->id) selected @endif>{{$v->sign_name}} - {{$v->template_code}}</option>
                    @endforeach
                    </optgroup>
                    @endforeach
                </select>
                <div class="invalid-feedback"></div>
            </div>
            <div class="form-group">
                <label for="">type</label>
                <select name="type" class="form-control">
                    @if(isset($dict['sms_type']))
                    @foreach($dict['sms_type'] as $key=>$val)
                        <option value="{{$key}}" @if($res['info']->type==$key) selected @endif>{{$val}}</option>
                    @endforeach
                    @endif
                </select>
                <div class="invalid-feedback"></div>
            </div>
            <div class="form-group">
                <label for="">每天同手机号限制条数</label>
                <input type="text" name="phone_limit" class="form-control " value="{{$res['info']->phone_limit?:5}}">
                <div class="invalid-feedback"></div>
            </div>
            <div class="form-group">
                <label for="">每天同ip限制条数</label>
                <input type="text" name="ip_limit" class="form-control " value="{{$res['info']->ip_limit?:20}}">
                <div class="invalid-feedback"></div>
            </div>
            <div class="form-group">
                <label for="">验证码有效时间（分钟）</label>
                <input type="text" name="expire" class="form-control " value="{{$res['info']->expire?:10}}">
                <div class="invalid-feedback"></div>
            </div>
            <div class="form-group">
                <label for="">总条数</label>
                <input type="text" name="total_num" class="form-control " value="{{$res['info']->total_num?:0}}">
                <div class="invalid-feedback"></div>
            </div>
            <div class="form-group">
                <label for="">已发条数</label>
                <input type="text" name="used_num" class="form-control " value="{{$res['info']->used_num?:0}}">
                <div class="invalid-feedback"></div>
            </div>
            <div class="form-group">
                <label for="">状态</label>
                <select name="status"  class="form-control">
                    @if(isset($dict['status']))
                        @foreach($dict['status'] as $key=>$val)
                            <option value="{{$key}}" @if($res['info']->status==$key) selected @endif>{{$val}}</option>
                        @endforeach
                    @endif
                </select>
                <div class="invalid-feedback"></div>
            </div>
            <button class="btn btn-primary" type="submit">保存</button>
        </div>
    </form>

</div>
<style>

</style>
<script>

</script>
