@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <div class="layui-btn-group ">
                @can('message.message.destroy')
                    <button class="layui-btn layui-btn-sm layui-btn-danger" id="listDelete">删除</button>
                @endcan
            </div>
        </div>
        <div class="layui-card-body">
            <table id="dataTable" lay-filter="dataTable"></table>
            <script type="text/html" id="options">
                <div class="layui-btn-group">
                    @can('message.message.destroy')
                        <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del">删除</a>
                    @endcan
                </div>
            </script>
            <script type="text/html" id="read">
                <input @{{# if(d.read==2){ }}disabled@{{# } }} message-id="@{{ d.id }}" type="checkbox" @{{# if(d.read==1){ }}lay-filter="read"@{{# } }} lay-skin="switch" lay-text="未读|已读" @{{ d.read==1?'checked':'' }} >
            </script>
        </div>
    </div>
@endsection

@section('script')
    @can('message.message')
        <script>
            layui.use(['layer','table','form'],function () {
                var layer = layui.layer;
                var form = layui.form;
                var table = layui.table;
                //用户表格初始化
                var dataTable = table.render({
                    elem: '#dataTable'
                    ,height: 500
                    ,url: "{{ route('admin.message.mine') }}" //数据接口
                    ,page: true //开启分页
                    ,cols: [[ //表头
                        {checkbox: true,fixed: true}
                        ,{field: 'id', title: 'ID', sort: true,width:80}
                        ,{field: 'title', title: '标题'}
                        ,{field: 'content', title: '内容'}
                        ,{field: 'send_name', title: '发送人'}
                        ,{field: 'read', title: '已读/未读',width:100,toolbar: '#read'}
                        ,{field: 'created_at', title: '创建时间'}
                        ,{fixed: 'right', width: 220, align:'center', toolbar: '#options'}
                    ]]
                });

                //监听工具条
                table.on('tool(dataTable)', function(obj){ //注：tool是工具条事件名，dataTable是table原始容器的属性 lay-filter="对应的值"
                    var data = obj.data //获得当前行数据
                        ,layEvent = obj.event; //获得 lay-event 对应的值
                    if(layEvent === 'del'){
                        layer.confirm('确认删除吗？', function(index){
                            $.post("{{ route('admin.message.destroy') }}",{_method:'delete',ids:[data.id]},function (result) {
                                if (result.code==0){
                                    obj.del(); //删除对应行（tr）的DOM结构
                                }
                                layer.close(index);
                                layer.msg(result.msg)
                            });
                        });
                    } else if(layEvent === 'edit'){
                        location.href = '/admin/message/'+data.id+'/edit';
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
                            $.post("{{ route('admin.message.destroy') }}",{_method:'delete',ids:ids},function (result) {
                                if (result.code==0){
                                    dataTable.reload()
                                }
                                layer.close(index);
                                layer.msg(result.msg,)
                            });
                        })
                    }else {
                        layer.msg('请选择删除项')
                    }
                });

                //已读未读
                form.on('switch(read)', function(data){
                    var othis = $(data.othis);
                    var obj = $(data.elem)
                    var id = obj.attr('message-id');
                    var url = '/admin/message/'+id+'/read';
                    $.post(url,{_token:"{{csrf_token()}}"},function (res) {
                        layer.msg(res.msg,{time:1000},function () {
                            if (res.code==0){
                                obj.attr('disabled',true);
                                othis.addClass("layui-checkbox-disbaled layui-disabled");
                                form.render('checkbox','read');
                                if ($("#unreadMessage").length>0){
                                    var currentNum = parseInt($("#unreadMessage").text())
                                    var updateNum = currentNum-1 >=0 ? currentNum : 0;
                                    $("#unreadMessage").text(updateNum)
                                }

                            }
                        })
                    },'json')
                });
            });
        </script>
    @endcan
@endsection