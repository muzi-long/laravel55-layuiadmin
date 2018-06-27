@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>添加广告信息</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="{{route('admin.advert.store')}}" method="post">
                @include('admin.advert._form')
            </form>
        </div>
    </div>
@endsection

@section('script')
    @include('admin.advert._js')
@endsection