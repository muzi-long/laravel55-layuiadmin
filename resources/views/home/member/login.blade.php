@extends('home.base')

@section('content')
    <form class="layui-form" action="{{route('home.member.login')}}" method="post">
        {{csrf_field()}}

        <div class="layui-form-item">
            <label for="" class="layui-form-label">手机号</label>
            <div class="layui-input-inline">
                <input type="text" name="phone" value="{{$member->phone??old('phone')}}" required="phone" lay-verify="phone" placeholder="请输入手机号" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label for="" class="layui-form-label">密码</label>
            <div class="layui-input-inline">
                <input type="password" name="password" placeholder="请输入密码" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label for="" class="layui-form-label">验证码</label>
            <div class="layui-input-inline">
                <input type="text" name="captcha"  lay-verify="required" class="layui-input">
            </div>
            <div class="layui-input-inline">
                <img src="{{captcha_src()}}" alt="">
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-input-block">
                <button type="submit" class="layui-btn" lay-submit="" lay-filter="formDemo">登录</button>
            </div>
        </div>
    </form>
@endsection