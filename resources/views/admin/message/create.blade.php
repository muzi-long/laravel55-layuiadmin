@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>推送消息</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="{{route('admin.message.store')}}" method="post">
                {{csrf_field()}}
                <div class="layui-form-item">
                    <label for="" class="layui-form-label">标题</label>
                    <div class="layui-input-inline">
                        <input type="text" name="title" value="{{ old('title') }}" lay-verify="required" placeholder="请输入标题" class="layui-input" >
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="" class="layui-form-label">内容</label>
                    <div class="layui-input-inline">
                        <textarea name="content" class="layui-textarea" cols="30" rows="6" lay-verify="required" placeholder="请输入内容">{{old('content')}}</textarea>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="" class="layui-form-label">接收人</label>
                    <div class="layui-form-mid layui-word-aux" style="float: none">
                        <button class="layui-btn layui-bg-green layui-btn-xs"></button>-后台用户
                        <button class="layui-btn layui-bg-black layui-btn-xs"></button>-前台用户
                        <button type="button" class="layui-btn layui-btn-xs" onclick="getUser()">点击选择</button>
                    </div>
                    <div class="layui-input-block">
                        <ul class="userBox layui-clear userBox2"></ul>
                        <ul class="userBox layui-clear userBox3"></ul>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button type="submit" class="layui-btn" lay-submit="" lay-filter="formDemo">确 认</button>
                        <a  class="layui-btn" href="{{route('admin.message')}}" >返 回</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <style>
        .userBox{

        }
        .userBox li{
            display: inline-block;
            float: left;
            padding:10px 22px;
            color: #fff;
            border-radius: 4px;
            margin:0 10px 10px 0;
            position: relative;
        }
        .userBox li.li2{
            background-color: #009688;
        }
        .userBox li.li3{
            background-color: #393D49;
        }
        .userBox li i{
            display: block;
            width: 10px;
            height:10px;
            line-height: 10px;
            color: #fff;
            text-align: center;
            border:1px solid #fff;
            border-radius: 50%;
            position: absolute;
            top:2px;
            right: 2px;
            cursor: pointer;
        }
    </style>
    <script>
        function getUser() {
            layer.open({
                type:2,
                title:'选择用户',
                area : ['630px','430px'],
                content:"{{route('admin.message.getUser')}}"
            })
        }
        function removeLi(obj) {
            $(obj).parent('li').remove();
        }
    </script>
@endsection