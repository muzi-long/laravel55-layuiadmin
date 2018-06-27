@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>更新权限</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="{{route('admin.permission.update',['permission'=>$permission])}}" method="post">
                {{method_field('put')}}
                <input type="hidden" name="id" value="{{ $permission->id }}">
                @include('admin.permission._from')
            </form>
        </div>
    </div>
@endsection

@section('script')
    @include('admin.permission._js')
@endsection