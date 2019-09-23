@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>添加配置组</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="{{route('admin.config_group.store')}}" method="post">
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