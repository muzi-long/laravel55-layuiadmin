@extends('admin.login_register.base')

@section('content')
    <div class="layadmin-user-login-box layadmin-user-login-body layui-form">
        <form action="{{route('admin.login')}}" method="post">
            {{csrf_field()}}
            <div class="layui-form-item">
                <label class="layadmin-user-login-icon layui-icon layui-icon-username" for="LAY-user-login-username"></label>
                <input type="text" name="username" value="{{old('username')}}" lay-verify="required" placeholder="用户名" class="layui-input">
            </div>
            <div class="layui-form-item">
                <label class="layadmin-user-login-icon layui-icon layui-icon-password" for="LAY-user-login-password"></label>
                <input type="password" name="password"  lay-verify="required" placeholder="密码" class="layui-input">
            </div>
            <div class="layui-form-item">
                <div class="layui-row">
                    <div class="layui-col-xs7">
                        <label class="layadmin-user-login-icon layui-icon layui-icon-vercode" for="LAY-user-login-vercode"></label>
                        <input type="text" name="captcha" id="LAY-user-login-vercode" lay-verify="required" placeholder="图形验证码" class="layui-input">
                    </div>
                    <div class="layui-col-xs5">
                        <div style="margin-left: 10px;">
                            <img src="{{captcha_src()}}" class="layadmin-user-login-codeimg" id="LAY-user-get-vercode"  style="cursor: pointer" onclick="this.src='{{captcha_src()}}'+Math.random()">
                        </div>
                    </div>
                </div>
            </div>

            <div class="layui-form-item">
                <button type="submit" class="layui-btn layui-btn-fluid" lay-submit lay-filter="">登 入</button>
            </div>
        </form>
    </div>
@endsection