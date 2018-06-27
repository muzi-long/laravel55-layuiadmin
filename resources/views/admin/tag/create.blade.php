@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>添加标签</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="{{route('admin.tag.store')}}" method="post">
                @include('admin.tag._form')
            </form>
        </div>
    </div>
@endsection