@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <div class="layui-btn-group">
                @can('config.advert.destroy')
                    <button class="layui-btn layui-btn-sm layui-btn-danger" id="listDelete">删除</button>
                @endcan
                @can('config.advert.create')
                    <a class="layui-btn layui-btn-sm" href="{{ route('admin.advert.create') }}">添加</a>
                @endcan
            </div>
            <div class="layui-form" >
                <div class="layui-input-inline">
                    <select name="position_id" lay-verify="required" id="position_id">
                        <option value="">请选择广告位置</option>
                        @foreach($positions as $position)
                            <option value="{{ $position->id }}" >{{ $position->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="layui-input-inline">
                    <input type="text" name="title" id="title" placeholder="请输入标题" class="layui-input">
                </div>
                <div class="layui-input-inline">
                    <button class="layui-btn" id="searchBtn">搜索</button>
                </div>
            </div>
        </div>
        <div class="layui-card-body">
            <table id="dataTable" lay-filter="dataTable"></table>
            <script type="text/html" id="options">
                <div class="layui-btn-group">
                    @can('config.advert.edit')
                        <a class="layui-btn layui-btn-sm" lay-event="edit">编辑</a>
                    @endcan
                    @can('config.advert.destroy')
                        <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del">删除</a>
                    @endcan
                </div>
            </script>
            <script type="text/html" id="position">
                @{{ d.position.name }}
            </script>
            <script type="text/html" id="thumb">
                <a href="@{{d.thumb}}" target="_blank" title="点击查看"><img src="@{{d.thumb}}" alt="" width="28" height="28"></a>
            </script>
        </div>
    </div>
@endsection

@section('script')
    @can('config.advert')
        <script>
            layui.use(['layer','table','form'],function () {
                var layer = layui.layer;
                var form = layui.form;
                var table = layui.table;
                //用户表格初始化
                var dataTable = table.render({
                    elem: '#dataTable'
                    ,height: 500
                    ,autoSort: false
                    ,url: "{{ route('admin.advert.data') }}" //数据接口
                    ,page: true
                    ,done: function(res, curr, count){
                        //接口回调，处理一些和表格相关的辅助事项
                        if(res.data.length==0 && count>0){
                            var page_now;
                            if(curr-1>0){
                                page_now =curr-1;
                            }else{
                                page_now = 1 ;
                            }
                            dataTable.reload({
                                page: {
                                    curr: page_now //重新从第 1 页开始
                                }
                            });
                        }
                    }
                    ,cols: [[ //表头
                        {checkbox: true,fixed: true}
                        ,{field: 'id', title: 'ID', sort: true,width:80}
                        ,{field: 'position', title: '广告位置',toolbar:'#position'}
                        ,{field: 'title', title: '广告位标题'}
                        ,{field: 'thumb', title: '图片',toolbar:'#thumb'}
                        ,{field: 'link', title: '链接',width:200}
                        ,{field: 'sort', title: '排序', sort: true,}
                        ,{field: 'created_at', title: '创建时间'}
                        ,{field: 'updated_at', title: '更新时间'}
                        ,{fixed: 'right', width: 220, align:'center', toolbar: '#options'}
                    ]]
                });

                //监听工具条
                table.on('tool(dataTable)', function(obj){ //注：tool是工具条事件名，dataTable是table原始容器的属性 lay-filter="对应的值"
                    var data = obj.data //获得当前行数据
                        ,layEvent = obj.event; //获得 lay-event 对应的值
                    if(layEvent === 'del'){
                        layer.confirm('确认删除吗？', function(index){
                            $.post("{{ route('admin.advert.destroy') }}",{_method:'delete',ids:[data.id]},function (result) {
                                if (result.code==0){
                                    obj.del(); //删除对应行（tr）的DOM结构
                                    dataTable.reload();
                                }
                                layer.close(index);
                                layer.msg(result.msg,{icon:6})
                            });
                        });
                    } else if(layEvent === 'edit'){
                        layer.open({
                            type: 2,
                            title:'编辑广告',
                            shadeClose:true, area: ['100%', '100%'],
                            content: '/admin/advert/'+data.id+'/edit',
                            end:function () {
                                dataTable.reload();
                            }
                        });
                    }
                });
                //按钮批量删除
                $("#listDelete").click(function () {
                    var ids = []
                    var hasCheck = table.checkStatus('dataTable')
                    var hasCheckData = hasCheck.data
                    if (hasCheckData.length>0){
                        $.each(hasCheckData,function (index,element) {
                            ids.push(element.id)
                        })
                    }
                    if (ids.length>0){
                        layer.confirm('确认删除吗？', function(index){
                            $.post("{{ route('admin.advert.destroy') }}",{_method:'delete',ids:ids},function (result) {
                                if (result.code==0){
                                    dataTable.reload();
                                }
                                layer.close(index);
                                layer.msg(result.msg,{icon:6})
                            });
                        })
                    }else {
                        layer.msg('请选择删除项',{icon:5})
                    }
                })

                //搜索
                $("#searchBtn").click(function () {
                    var positionId = $("#position_id").val()
                    var title = $("#title").val();
                    dataTable.reload({
                        where:{position_id:positionId,title:title},
                        page:{curr:1}
                    })
                })
                //监听排序事件
                table.on('sort(dataTable)', function(obj){ //注：tool是工具条事件名，test是table原始容器的属性 lay-filter="对应的值"

                    //尽管我们的 table 自带排序功能，但并没有请求服务端。
                    //有些时候，你可能需要根据当前排序的字段，重新向服务端发送请求，从而实现服务端排序，如：
                    table.reload('dataTable', {
                        initSort: obj //记录初始排序，如果不设的话，将无法标记表头的排序状态。
                        ,where: { //请求参数（注意：这里面的参数可任意定义，并非下面固定的格式）
                            field: obj.field //排序字段
                            ,order: obj.type //排序方式
                        }
                    });
                });
            })
        </script>
    @endcan
@endsection