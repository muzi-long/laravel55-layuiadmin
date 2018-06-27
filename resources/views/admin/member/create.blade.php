@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>添加用户</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="{{route('admin.member.store')}}" method="post">
                @include('admin.member._form')
            </form>
        </div>
    </div>
@endsection
@section('script')
    @include('admin.member._js')
@endsection
