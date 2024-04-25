
<div class="top-bar">
    <h5 class="nav-title">{!! $res['breadcrumb'] !!}</h5>
</div>
<div class="imain">
    <form method="post" action="/sms_admin/sms/test_aliyun" class="save_form">
        @csrf
        <div class="">
            <div class="form-group">
                <label for="">手机号码</label>
                <input type="text" name="phone" class="form-control " value="">
                <div class="invalid-feedback"></div>
            </div>
            <div class="form-group">
                <label for="">验证码</label>
                <input type="text" name="sms_code" class="form-control " value="">
                <div class="invalid-feedback"></div>
            </div>
            <div class="form-group">
                <label for="">签名 - 模板Code</label>
                <select name="template_id"  class="form-control">
                    @foreach($res['template'] as $key=>$val)
                        <option value="{{$val->id}}" >{{$val->sign_name}} - {{$val->template_code}}</option>
                    @endforeach
                </select>
                <div class="invalid-feedback"></div>
            </div>
            <button class="btn btn-primary" type="submit">发送</button>
        </div>
    </form>

</div>
<style>

</style>
<script>

</script>
