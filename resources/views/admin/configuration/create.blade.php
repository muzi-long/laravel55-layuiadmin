@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>添加配置</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="{{route('admin.configuration.store')}}" method="post">
                {{csrf_field()}}
                <div class="layui-form-item">
                    <label for="" class="layui-form-label">配置组</label>
                    <div class="layui-input-inline">
                        <select name="group_id" lay-verify="required">
                            <option value=""></option>
                            @foreach($groups as $group)
                            <option value="{{$group->id}}">{{$group->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="" class="layui-form-label">类型</label>
                    <div class="layui-input-inline">
                        <select name="type" >
                            <option value="input">输入框</option>
                            <option value="textarea">文本域</option>
                            <option value="radio">单选</option>
                            <option value="select">下拉</option>
                            <option value="image">图片</option>
                        </select>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="" class="layui-form-label">配置名称</label>
                    <div class="layui-input-inline">
                        <input type="text" name="label" value="{{ old('label') }}" lay-verify="required" placeholder="请输入名称" class="layui-input" >
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="" class="layui-form-label">配置字段</label>
                    <div class="layui-input-inline">
                        <input type="text" name="key" value="{{ old('key') }}" lay-verify="required" placeholder="请输入,如name" class="layui-input" >
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="" class="layui-form-label">配置值</label>
                    <div class="layui-input-inline">
                        <input type="text" name="val" value="{{ old('val') }}" lay-verify="required" placeholder="请输入" class="layui-input" >
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="" class="layui-form-label">输入提示</label>
                    <div class="layui-input-inline">
                        <input type="text" name="tips" value="{{ old('tips') }}" placeholder="请输入" class="layui-input" >
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="" class="layui-form-label">排序</label>
                    <div class="layui-input-inline">
                        <input type="number" name="sort" value="{{ old('sort',10) }}" placeholder="请输入" class="layui-input" >
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button type="submit" class="layui-btn" lay-submit="" lay-filter="formDemo">确 认</button>
                        <a  class="layui-btn" href="{{route('admin.configuration')}}" >返 回</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        layui.use(['element','form'],function () {

        })
    </script>
@endsection