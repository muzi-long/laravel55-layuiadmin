{{csrf_field()}}
<div class="layui-form-item">
    <label for="" class="layui-form-label">广告位置</label>
    <div class="layui-input-inline">
        <select name="position_id" lay-verify="required">
            <option value=""></option>
            @foreach($positions as $position)
                <option value="{{ $position->id }}" {{ $position->selected??'' }} >{{ $position->name }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">广告标题</label>
    <div class="layui-input-inline">
        <input type="text" name="title" value="{{ $advert->title ?? old('name') }}" lay-verify="required" placeholder="请输入标题" class="layui-input" >
    </div>
</div>
<div class="layui-form-item">
    <label for="" class="layui-form-label">缩略图</label>
    <div class="layui-input-block">
        <div class="layui-upload">
            <button type="button" class="layui-btn" id="uploadPic"><i class="layui-icon">&#xe67c;</i>图片上传</button>
            <div class="layui-upload-list" >
                <ul id="layui-upload-box" class="layui-clear">
                    @if(isset($advert->thumb))
                        <li><img src="{{ $advert->thumb }}" /><p>上传成功</p></li>
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
        <input type="number" name="sort" value="{{ $advert->sort ?? 0 }}" lay-verify="required|number" placeholder="请输入数字" class="layui-input" >
    </div>
</div>
<div class="layui-form-item">
    <label for="" class="layui-form-label">链接</label>
    <div class="layui-input-inline">
        <input type="text" name="link" value="{{ $advert->link ?? '' }}" placeholder="请输入链接地址" class="layui-input" >
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
        <a  class="layui-btn" href="{{route('admin.advert')}}" >返 回</a>
    </div>
</div>