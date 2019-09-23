@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <div class="layui-btn-group">
                @can('system.configuration.create')
                    <a class="layui-btn layui-btn-sm" href="{{ route('admin.configuration.create') }}">添 加</a>
                @endcan
            </div>
        </div>
        <div class="layui-card-body">
            <div class="layui-tab layui-tab-brief" lay-filter="docDemoTabBrief">
                <ul class="layui-tab-title">
                    @foreach($groups as $group)
                        <li @if($loop->index==0) class="layui-this" @endif >{{$group->name}}</li>
                    @endforeach
                </ul>
                <div class="layui-tab-content">
                    @foreach($groups as $group)
                        <div class="layui-tab-item @if($loop->index==0) layui-show @endif">
                            <form class="layui-form">
                                @foreach($group->configurations as $configuration)
                                    <div class="layui-form-item">
                                        <label for="" class="layui-form-label" style="width: 120px">{{$configuration->label}}</label>
                                        <div class="layui-input-inline" style="min-width: 600px">
                                            @switch($configuration->type)
                                                @case('input')
                                                    <input type="input" class="layui-input" name="{{$configuration->key}}" value="{{$configuration->val}}">
                                                    @break
                                                @case('textarea')
                                                    <textarea name="{{$configuration->key}}" class="layui-textarea">{{$configuration->val}}</textarea>
                                                    @break
                                                @case('select')
                                                    <select name="{{$configuration->key}}">
                                                        @if($configuration->content)
                                                            @foreach(explode("|",$configuration->content) as $v)
                                                                @php
                                                                    $key = \Illuminate\Support\Str::before($v,':');
                                                                    $val = \Illuminate\Support\Str::after($v,':');
                                                                @endphp
                                                                <option value="{{$key}}" @if($key==$configuration->val) selected @endif >{{$val}}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    @break
                                                @case('radio')
                                                    @if($configuration->content)
                                                        @foreach(explode("|",$configuration->content) as $v)
                                                            @php
                                                                $key = \Illuminate\Support\Str::before($v,':');
                                                                $val = \Illuminate\Support\Str::after($v,':');
                                                            @endphp
                                                            <input type="radio" name="{{$configuration->key}}" value="{{$key}}" @if($key==$configuration->val) checked @endif title="{{$val}}">
                                                        @endforeach
                                                    @endif
                                                    @break
                                                @case('image')
                                                    <div class="layui-upload">
                                                        <button type="button" class="layui-btn layui-btn-sm uploadPic"><i class="layui-icon">&#xe67c;</i>图片上传</button>
                                                        <div class="layui-upload-list" >
                                                            <ul class="layui-upload-box layui-clear">
                                                                @if($configuration->val)
                                                                    <li><img src="{{ $configuration->val }}" /><p>上传成功</p></li>
                                                                @endif
                                                            </ul>
                                                            <input type="hidden" class="layui-upload-input" name="{{$configuration->key}}" value="{{$configuration->val}}">
                                                        </div>
                                                    </div>
                                                    @break
                                                @default
                                                    @break
                                            @endswitch
                                        </div>
                                        <div class="layui-form-mid layui-word-aux">{{$configuration->tips}}</div>
                                    </div>
                                @endforeach
                                    <div class="layui-form-item">
                                        <div class="layui-input-block">
                                            <button type="submit" class="layui-btn" lay-submit lay-filter="config_group">确 认</button>
                                        </div>
                                    </div>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @can('system.configuration')
        <script>
            layui.use(['layer', 'table', 'form','upload','element'], function () {
                var $ = layui.jquery;
                var layer = layui.layer;
                var form = layui.form;
                var table = layui.table;
                var upload = layui.upload;

                //图片
                $(".uploadPic").each(function (index,elem) {
                    upload.render({
                        elem: $(elem)
                        ,url: '{{ route("api.upload") }}'
                        ,multiple: false
                        ,data:{"_token":"{{ csrf_token() }}"}
                        ,done: function(res){
                            //如果上传失败
                            if(res.code == 0){
                                layer.msg(res.msg,{icon:1},function () {
                                    $(elem).parent('.layui-upload').find('.layui-upload-box').html('<li><img src="'+res.url+'" /><p>上传成功</p></li>');
                                    $(elem).parent('.layui-upload').find('.layui-upload-input').val(res.url);
                                })
                            }else {
                                layer.msg(res.msg,{icon:2})
                            }
                        }
                    });
                })

                //提交
                form.on('submit(config_group)',function (data) {
                    var parm = data.field;
                    parm['_method'] = 'put';
                    var load = layer.load();
                    $.post("{{route('admin.configuration.update')}}",data.field,function (res) {
                        layer.close(load);
                        if (res.code==0){
                            layer.msg(res.msg,{icon:1})
                        }else {
                            layer.msg(res.msg,{icon:2});
                        }
                    });
                    return false;
                });
            })
        </script>
    @endcan
@endsection