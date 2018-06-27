@extends('admin.base')

@section('content')
    <div class="layui-elem-quote">更新用户</div>
    <form class="layui-form" action="{{route('user.update',['role'=>$user])}}" method="post">
        {{csrf_field()}}
        {{method_field('put')}}
        <div class="layui-form-item">
            <label for="" class="layui-form-label">昵&nbsp;&nbsp;&nbsp;称</label>
            <div class="layui-input-inline">
                <input type="text" name="nickname" value="{{$user->nickname}}" required="" lay-verify="required" placeholder="请输入昵称" autocomplete="off" class="layui-input" >
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">邮&nbsp;&nbsp;&nbsp;箱</label>
            <div class="layui-input-inline">
                <input type="email" name="email" value="{{$user->email}}" required="" lay-verify="required" placeholder="请输入Email" autocomplete="off" class="layui-input" >
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">用户名</label>
            <div class="layui-input-inline">
                <input type="text" name="name" value="{{$user->name}}" required="" lay-verify="required" placeholder="请输入用户名" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">手机号</label>
            <div class="layui-input-inline">
                <input type="text" name="tel" value="{{$user->tel}}" required="" lay-verify="required" placeholder="请输入手机号" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">密&nbsp;&nbsp;&nbsp;码</label>
            <div class="layui-input-inline">
                <input type="password" id="password" name="password" placeholder="请输入密码" autocomplete="off" class="layui-input">
            </div>
            <div class="layui-form-mid layui-word-aux">不修改密码则留空</div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">确&nbsp;&nbsp;&nbsp;认</label>
            <div class="layui-input-inline">
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="请输入密码" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button type="submit" class="layui-btn" lay-submit="" lay-filter="formDemo">确 认</button>
                <a class="layui-btn" href="{{route('user.index')}}" >返 回</a>
            </div>
        </div>
    </form>
@endsection


