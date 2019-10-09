@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>站点配置</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="{{route('admin.site.update')}}" method="post">
                {{csrf_field()}}
                {{method_field('put')}}
                <div class="layui-form-item">
                    <label for="" class="layui-form-label">网站名称<em style="color: red">*</em></label>
                    <div class="layui-input-block">
                        <input type="text" name="site_name" value="{{ $config['site_name'] ?? old('site_name') }}" lay-verify="required" placeholder="请输入网站名称" class="layui-input" >
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="" class="layui-form-label">网站logo</label>
                    <div class="layui-input-block">
                        <div class="layui-upload">
                            <button type="button" class="layui-btn" id="uploadPic"><i class="layui-icon">&#xe67c;</i>图片上传</button>
                            <div class="layui-upload-list" >
                                <ul id="layui-upload-box" class="layui-clear">
                                    @if(isset($config['logo']))
                                        <li><img src="{{ $config['logo'] }}" /><p>上传成功</p></li>
                                    @elseif(old('logo') != null)
                                        <li><img src="{{ old('logo') }}" /><p>上传成功</p></li>
                                    @endif
                                </ul>
                                <input type="hidden" name="logo" id="logo" value="{{ $config['logo']??'' }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="" class="layui-form-label">站点标题<em style="color: red">*</em></label>
                    <div class="layui-input-block">
                        <input type="text" name="title" value="{{ $config['title']?? old('title') }}" lay-verify="required" placeholder="请输入标题" class="layui-input" >
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="" class="layui-form-label">站点关键词<em style="color: red">*</em></label>
                    <div class="layui-input-block">
                        <input type="text" name="keywords" value="{{ $config['keywords']?? old('keywords') }}" lay-verify="required" placeholder="请输入关键词" class="layui-input" >
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="" class="layui-form-label">站点描述<em style="color: red">*</em></label>
                    <div class="layui-input-block">
                        <textarea class="layui-textarea" name="description" cols="30" rows="10" lay-verify="required" >{{ $config['description']?? old('description') }}</textarea>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="" class="layui-form-label">CopyRight</label>
                    <div class="layui-input-block">
                        <input type="text" name="copyright" value="{{ $config['copyright']?? old('copyright')  }}" lay-verify="required" placeholder="请输入copyright" class="layui-input" >
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="" class="layui-form-label">电话</label>
                    <div class="layui-input-block">
                        <input type="text" name="phone" value="{{ $config['phone']?? old('phone') }}"  placeholder="请输入电话" class="layui-input" >
                    </div>
                </div>
             {{--   <div class="layui-form-item">
                    <label for="" class="layui-form-label">城市</label>
                    <div class="layui-input-block">
                        <input type="text" name="city" value="{{ $config['city']?? old('city') }}" lay-verify="required" placeholder="请输入城市" class="layui-input" >
                    </div>
                </div>--}}
                <div class="layui-form-item">
                    <label for="" class="layui-form-label">附件类型<em style="color: red">*</em></label>
                    <div class="layui-input-block">
                        <input type="text" name="img_type" value="{{ $config['img_type']?? old('img_type') }}" lay-verify="required" placeholder="请输入附件类型,用;分开" class="layui-input" >
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="" class="layui-form-label">附件大小<em style="color: red">*</em></label>
                    <div class="layui-input-block">
                        <input type="number" name="img_size" value="{{ $config['img_size']?? old('img_size') }}" lay-verify="required" placeholder="附件大小" class="layui-input" style="width: 300px;display: inline-block" > <span style="display: inline-block">M</span>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="" class="layui-form-label">统计代码</label>
                    <div class="layui-input-block">
                        <textarea class="layui-textarea" name="count_code" cols="30" rows="10">{{ $config['count_code']?? old('count_code') }}</textarea>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="" class="layui-form-label">操作日志</label>
                    <div class="layui-input-block">
                        <input type="radio" name="operation_log_enable" value="1" title="开启" @if(array_get($config,'operation_log_enable',0)==1) checked @endif>
                        <input type="radio" name="operation_log_enable" value="0" title="关闭" @if(array_get($config,'operation_log_enable',0)==0) checked @endif>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button type="submit" class="layui-btn" lay-submit="" lay-filter="formDemo">确 认</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('script')
<script>
    layui.use(['upload'],function () {
        var upload = layui.upload
        //普通图片上传
        var uploadInst = upload.render({
            elem: '#uploadPic'
            ,url: '{{ route("upload.image") }}'
            ,multiple: false
            ,data:{"_token":"{{ csrf_token() }}"}
            ,before: function(obj){
                //预读本地文件示例，不支持ie8
                /*obj.preview(function(index, file, result){
                 $('#layui-upload-box').append('<li><img src="'+result+'" /><p>待上传</p></li>')
                 });*/
                obj.preview(function(index, file, result){
                    $('#layui-upload-box').html('<li><img src="'+result+'" /></li>')
                });

            }
            ,done: function(res){
                //如果上传失败
                if(res.code == 0){
                    $("#logo").val(res.url);
                    $('#layui-upload-box li p').text('上传成功');
                    return layer.msg(res.msg);
                }
                var old_logo = '{{ $config['logo'] }}';

                $('#layui-upload-box').html('<li><img src="'+old_logo+'" /></li>')
                return layer.msg(res.msg);
            }
        });
    });
</script>
@endsection