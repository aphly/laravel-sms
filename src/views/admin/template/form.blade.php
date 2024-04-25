
<div class="top-bar">
    <h5 class="nav-title">{!! $res['breadcrumb'] !!}</h5>
</div>
<div class="imain">
    <form method="post" @if($res['info']->id) action="/sms_admin/template/save?id={{$res['info']->id}}" @else action="/sms_admin/template/save" @endif class="save_form">
        @csrf
        <div class="">
            <div class="form-group">
                <label for="">签名</label>
                <input type="text" name="sign_name" class="form-control " value="{{$res['info']->sign_name}}">
                <div class="invalid-feedback"></div>
            </div>
            <div class="form-group">
                <label for="">模版Code</label>
                <input type="text" name="template_code" class="form-control " value="{{$res['info']->template_code}}">
                <div class="invalid-feedback"></div>
            </div>
            <div class="form-group">
                <label for="">通道</label>
                <select name="driver_id"  class="form-control">
                    @foreach($res['driver'] as $key=>$val)
                        <option value="{{$val->id}}" @if($res['info']->driver_id==$val->id) selected @endif>{{$val->name}}</option>
                    @endforeach
                </select>
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
