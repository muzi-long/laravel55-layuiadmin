@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>更新账号</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="{{route('admin.member.update',['member'=>$member])}}" method="post">
                <input type="hidden" name="id" value="{{$member->id}}">
                {{method_field('put')}}
                @include('admin.member._form')
            </form>
        </div>
    </div>
@endsection

@section('script')
    @include('admin.member._js')
@endsection
