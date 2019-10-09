{{csrf_field()}}

<div class="layui-form-item">
    <label for="" class="layui-form-label">父级</label>
    <div class="layui-input-block">
        @if(isset($permission_id))
            <select name="parent_id" lay-search>
                <option value="0">顶级权限</option>
                @forelse($permissions as $perm)
                    <option value="{{$perm['id']}}" {{ $perm['id'] == $permission_id? 'selected' : '' }} >{{$perm['display_name']}}</option>
                    @if(isset($perm['_child']))
                        @foreach($perm['_child'] as $childs)
                            <option value="{{$childs['id']}}" {{ $childs['id'] == $permission_id ? 'selected' : '' }} >┗━━{{$childs['display_name']}}</option>
                            @if(isset($childs['_child']))
                                @foreach($childs['_child'] as $lastChilds)
                                    <option value="{{$lastChilds['id']}}" {{ $lastChilds['id'] == $permission_id ? 'selected' : '' }} >┗━━━━{{$lastChilds['display_name']}}</option>
                                @endforeach
                            @endif
                        @endforeach
                    @endif
                @empty
                @endforelse
            </select>
            @else
            <select name="parent_id" lay-search>
                <option value="0">顶级权限</option>
                @forelse($permissions as $perm)
                    <option value="{{$perm['id']}}" {{ isset($permission->id) && $perm['id'] == $permission->parent_id ? 'selected' : '' }} >{{$perm['display_name']}}</option>
                    @if(isset($perm['_child']))
                        @foreach($perm['_child'] as $childs)
                            <option value="{{$childs['id']}}" {{ isset($permission->id) && $childs['id'] == $permission->parent_id ? 'selected' : '' }} >┗━━{{$childs['display_name']}}</option>
                            @if(isset($childs['_child']))
                                @foreach($childs['_child'] as $lastChilds)
                                    <option value="{{$lastChilds['id']}}" {{ isset($permission->id) && $lastChilds['id'] == $permission->parent_id ? 'selected' : '' }} >┗━━━━{{$lastChilds['display_name']}}</option>
                                @endforeach
                            @endif
                        @endforeach
                    @endif
                @empty
                @endforelse
            </select>
        @endif
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">名称</label>
    <div class="layui-input-block">
        <input type="text" name="name" value="{{$permission->name??old('name')}}" lay-verify="required" class="layui-input" placeholder="如：system.index">
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">显示名称</label>
    <div class="layui-input-block">
        <input type="text" name="display_name" value="{{$permission->display_name??old('display_name')}}" lay-verify="required" class="layui-input" placeholder="如：系统管理">
    </div>
</div>
<div class="layui-form-item">
    <label for="" class="layui-form-label">路由</label>
    <div class="layui-input-block">
        <input class="layui-input" type="text" name="route" value="{{$permission->route??old('route')}}" placeholder="如：admin.member" >
    </div>
</div>
<div class="layui-form-item">
    <label for="" class="layui-form-label">图标</label>
    <div class="layui-input-inline">
        <input class="layui-input" type="hidden" name="icon_id" value="{{$permission->icon_id??''}}">
    </div>
    <div class="layui-form-mid layui-word-aux" id="icon_box">
        <i class="layui-icon {{$permission->icon->class??''}}"></i> {{$permission->icon->name??''}}
    </div>
    <div class="layui-form-mid layui-word-aux">
        <button type="button" class="layui-btn layui-btn-xs" onclick="showIconsBox()">选择图标</button>
    </div>
</div>
<div class="layui-form-item">
    <div class="layui-input-block">
        <button type="submit" class="layui-btn" lay-submit="" >确 认</button>
        <div  class="layui-btn close-iframe" onclick="close_parent('{{$permission->name ??''}}','/admin/permission')">关闭</div>
    </div>
</div>
@section('script')
    @include('admin.permission._js')
    @include('admin.common_edit')
@endsection

