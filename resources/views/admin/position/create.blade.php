@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>添加广告位</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="{{route('admin.position.store')}}" method="post">
                @include('admin.position._form')
            </form>
        </div>
    </div>
@endsection