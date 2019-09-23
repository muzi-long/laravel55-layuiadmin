@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>更新配置组</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="{{route('admin.config_group.update',['id'=>$configGroup->id])}}" method="post">
                {{ method_field('put') }}
                @include('admin.config_group._form')
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