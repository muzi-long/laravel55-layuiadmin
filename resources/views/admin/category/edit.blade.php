@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>更新分类</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="{{route('admin.category.update',['id'=>$category->id])}}" method="post">
                {{ method_field('put') }}
                @include('admin.category._form')
            </form>
        </div>
    </div>
@endsection