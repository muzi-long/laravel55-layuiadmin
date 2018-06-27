@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>更新广告位</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="{{route('admin.position.update',['id'=>$position->id])}}" method="post">
                {{ method_field('put') }}
                @include('admin.position._form')
            </form>
        </div>
    </div>
@endsection