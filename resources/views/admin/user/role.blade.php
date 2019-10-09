@extends('admin.base')

@section('content')
    <style>
        .layui-form-checkbox span{width: 100px}
    </style>
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>用户【{{$user->realname}}】分配角色</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="{{route('admin.user.assignRole',['user'=>$user])}}" method="post">
                {{csrf_field()}}
                {{method_field('put')}}
                <div class="layui-form-item">
                    <label for="" class="layui-form-label">角色</label>
                    <div class="layui-input-block" style="width: 400px">
                        @forelse($roles as $role)
                            <input type="checkbox" name="roles[]" value="{{$role->id}}" title="{{$role->display_name}}" {{ $role->own ? 'checked' : ''  }} >
                        @empty
                            <div class="layui-form-mid layui-word-aux">还没有角色</div>
                        @endforelse
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button type="submit" class="layui-btn" lay-submit="" lay-filter="formDemo">确 认</button>
                        <div  class="layui-btn close-iframe" onclick="close_parent(true)">关闭</div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('script')
    @include('admin.common_edit')
@endsection


