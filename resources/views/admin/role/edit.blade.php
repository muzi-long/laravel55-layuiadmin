@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>更新角色</h2>
        </div>
        <div class="layui-card-body">
            <form action="{{route('admin.role.update',['id'=>$role->id])}}" method="post" class="layui-form">
                {{method_field('put')}}
                <input type="hidden" name="id" value="{{$role->id}}">
                @include('admin.role._form')
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