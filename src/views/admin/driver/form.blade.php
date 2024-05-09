
<div class="top-bar">
    <h5 class="nav-title">{!! $res['breadcrumb'] !!}</h5>
</div>
<div class="imain">
    <form method="post" @if($res['info']->id) action="/sms_admin/driver/save?id={{$res['info']->id}}" @else action="/sms_admin/driver/save" @endif class="save_form">
        @csrf
        <div class="">
            <div class="form-group">
                <label for="">名称</label>
                <input type="text" name="name" class="form-control " value="{{$res['info']->name}}">
                <div class="invalid-feedback"></div>
            </div>
            <div class="form-group">
                <label for="">Key id</label>
                <input type="text" name="key_id" class="form-control " value="{{$res['info']->key_id}}">
                <div class="invalid-feedback"></div>
            </div>
            <div class="form-group">
                <label for="">Key secret</label>
                <input type="text" name="key_secret" class="form-control " value="{{$res['info']->key_secret}}">
                <div class="invalid-feedback"></div>
            </div>

            <div class="form-group">
                <label for="">sdk_app_id(腾讯云特有)</label>
                <input type="text" name="sdk_app_id" class="form-control " value="{{$res['info']->sdk_app_id}}">
                <div class="invalid-feedback"></div>
            </div>
            <div class="form-group">
                <label for="">sdk_app_key(腾讯云特有)</label>
                <input type="text" name="sdk_app_key" class="form-control " value="{{$res['info']->sdk_app_key}}">
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
