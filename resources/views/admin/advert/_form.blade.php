{{csrf_field()}}
<div class="layui-form-item">
    <label for="" class="layui-form-label">广告位置<em style="color: red">*</em></label>
    <div class="layui-input-inline">
        <select name="position_id" lay-verify="required">
            <option value=""></option>
            @foreach($positions as $position)
                {{--<option value="{{ $position->id }}" {{ $position->selected??'' }} >{{ $position->name }}</option>--}}
                <option value="{{ $position->id ??old('position_id')}}" @if((isset($position_id)&&$position->id==$position_id)||!isset($position_id)&&old('position_id')==$position->id)selected @endif >{{ $position->name??old('name')}}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">广告标题<em style="color: red">*</em></label>
    <div class="layui-input-inline">
        <input type="text" name="title" value="{{ $advert->title ?? old('title') }}" lay-verify="required" placeholder="请输入标题" class="layui-input" >
    </div>
</div>
<div class="layui-form-item">
    <label for="" class="layui-form-label">缩略图<em style="color: red">*</em></label>
    <div class="layui-input-block">
        <div class="layui-upload">
            <button type="button" class="layui-btn" id="uploadPic"><i class="layui-icon">&#xe67c;</i>图片上传</button>
            <div class="layui-upload-list" >
                <ul id="layui-upload-box" class="layui-clear">
                    @if(isset($advert->thumb))
                        <li><img src="{{ $advert->thumb }}" /><p>上传成功</p></li>
                    @elseif(old('thumb') != null)
                        <li><img src="{{ old('thumb') }}" /><p>上传成功</p></li>
                    @endif
                </ul>
                <input type="hidden" name="thumb" id="thumb" value="{{ $advert->thumb??'' }}">
            </div>
        </div>
    </div>
</div>
<div class="layui-form-item">
    <label for="" class="layui-form-label">排序</label>
    <div class="layui-input-inline">
        <input type="number" name="sort" @if($advert->sort??old('sort')) value="{{$advert->sort??old('sort')}}" @else value="0" @endif lay-verify="required|number" placeholder="请输入数字" class="layui-input" >
    </div>
</div>
<div class="layui-form-item">
    <label for="" class="layui-form-label">链接</label>
    <div class="layui-input-inline">
        <input type="text" name="link" value="{{ $advert->link ?? old('link') }}"  placeholder="请输入链接地址" class="layui-input" >
    </div>
    <div class="layui-form-mid"><span class="layui-word-aux">格式：http://xxxxx</span></div>
</div>
<div class="layui-form-item">
    <label for="" class="layui-form-label">描述</label>
    <div class="layui-input-inline">
        <textarea name="description" placeholder="请输入描述" class="layui-textarea">{{$advert->description??old('description')}}</textarea>
    </div>
</div>
<div class="layui-form-item">
    <div class="layui-input-block">
        <button type="submit" class="layui-btn" lay-submit="" lay-filter="formDemo">确 认</button>
        <div  class="layui-btn close-iframe" onclick="close_parent('{{$position_id??''}}','/admin/advert')">关闭</div>
    </div>
</div>
@section('script')
    @include('admin.advert._js')
    @include('admin.common_edit')
@endsection