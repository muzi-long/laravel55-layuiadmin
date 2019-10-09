{{csrf_field()}}
<div class="layui-form-item">
    <label for="" class="layui-form-label">登录账号:</label>
    <div class="layui-input-inline">
        <input type="text" name="username" value="{{ $user->username ?? old('username') }}" lay-verify="required" placeholder="请输入登录账号" class="layui-input" >
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">真实姓名:</label>
    <div class="layui-input-inline">
        <input type="text" name="realname" value="{{ $user->realname ?? old('realname') }}" lay-verify="required" placeholder="请输入真实姓名" class="layui-input" >
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">电子邮箱:</label>
    <div class="layui-input-inline">
        <input type="email" name="email" value="{{$user->email??old('email')}}" lay-verify="email" placeholder="请输入电子邮箱" class="layui-input" >
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">联系电话:</label>
    <div class="layui-input-inline">
        <input type="text" name="phone" value="{{$user->phone??old('phone')}}" required="phone" lay-verify="phone" placeholder="请输入手机号" class="layui-input">
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">登录密码</label>
    <div class="layui-input-inline">
        <input type="password" name="password" placeholder="请输入登录密码" class="layui-input">
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">确认密码</label>
    <div class="layui-input-inline">
        <input type="password" name="password_confirmation" placeholder="请再次输入登录密码" class="layui-input">
    </div>
</div>
<div class="layui-form-item">
    <div class="layui-input-block">
        <button type="submit" class="layui-btn" lay-submit="" lay-filter="formDemo">确 认</button>
        <div  class="layui-btn close-iframe" onclick="close_parent('{{$user->username ??''}}','/admin/user')">关闭</div>
    </div>
</div>
@section('script')
    @include('admin.common_edit')
@endsection
