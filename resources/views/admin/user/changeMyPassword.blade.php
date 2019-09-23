@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header  layuiadmin-card-header-auto">
            <h2>更改密码</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="{{route('admin.user.changeMyPassword')}}" method="post">
                {{csrf_field()}}
                <div class="layui-form-item">
                    <label for="" class="layui-form-label">原密码</label>
                    <div class="layui-input-inline">
                        <input type="password" name="old_password" lay-verify="required" placeholder="请输入原密码" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="" class="layui-form-label">新密码</label>
                    <div class="layui-input-inline">
                        <input type="password" name="new_password" maxlength="14" lay-verify="required" placeholder="请输入新密码" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="" class="layui-form-label">确认密码</label>
                    <div class="layui-input-inline">
                        <input type="password" name="new_password_confirmation" maxlength="14" lay-verify="required" placeholder="请确认新密码" class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label for="" class="layui-form-label"></label>
                    <div class="layui-input-block">
                        <button class="layui-btn" lay-submit lay-filter="*">确 认</button>
                    </div>
                </div>
        </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        layui.use(['layer','table','form','element'],function () {
            var layer = layui.layer;
            var form = layui.form;
            var table = layui.table;

        })
    </script>
@endsection

