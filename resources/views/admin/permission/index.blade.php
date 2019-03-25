@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <div class="layui-btn-group ">
                @can('system.permission.create')
                            <a class="layui-btn layui-btn-sm" href="{{ route('admin.permission.create') }}" id="add_permission">添 加</a>
                @endcan
                <button class="layui-btn layui-btn-sm" id="returnParent" pid="0">返回上级</button>
            </div>
        </div>
        <div class="layui-card-body">
            <table id="dataTable" lay-filter="dataTable"></table>
            <script type="text/html" id="icon">
                @{{# if(d.icon_id!==null) {}}
                <i class="layui-icon @{{ d.icon.class }}"></i>
                @{{# } }}
            </script>
            <script type="text/html" id="options">
                <div class="layui-btn-group">
                    @can('system.permission')
                        <a class="layui-btn layui-btn-sm" lay-event="children">子权限</a>
                    @endcan
                    @can('system.permission.edit')
                        <a class="layui-btn layui-btn-sm" lay-event="edit">编辑</a>
                    @endcan
                    @can('system.permission.destroy')
                        <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del">删除</a>
                    @endcan
                </div>
            </script>
        </div>
    </div>
@endsection

@section('script')
    @can('system.permission')
    <script>
        (function($){
            var pid =$("#returnParent").attr('pid');
            if(pid == 0){
                $("#returnParent").hide();
            }
           
        })(jQuery)
        layui.use(['layer','table','form'],function () {
            var layer = layui.layer;
            var form = layui.form;
            var table = layui.table;
            //用户表格初始化
            var dataTable = table.render({
                elem: '#dataTable'
                ,height: 500
                ,url: "{{ route('admin.data') }}" //数据接口
                ,where:{model:"permission"}
                ,page: true
                ,done: function(res, curr, count){
                    //接口回调，处理一些和表格相关的辅助事项
                   $("#add_permission").attr('href','/admin/permission/create?parent_id='+res.parent_id);
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
                                curr: page_now
                            }
                        });
                    }
                }
                ,cols: [[ //表头
                    {field: 'id', title: 'ID', sort:true,width:80}
                    ,{field: 'name', title: '权限名称'}
                    ,{field: 'display_name', title: '显示名称'}
                    ,{field: 'route', title: '路由'}
                    ,{field: 'icon_id', title: '图标', toolbar:'#icon'}
                    ,{field: 'created_at', title: '创建时间'}
                    ,{field: 'updated_at', title: '更新时间'}
                    ,{fixed: 'right', width: 260, align:'center', toolbar: '#options'}
                ]]
            });

            //监听工具条
            table.on('tool(dataTable)', function(obj){ //注：tool是工具条事件名，dataTable是table原始容器的属性 lay-filter="对应的值"
                var data = obj.data //获得当前行数据
                    ,layEvent = obj.event; //获得 lay-event 对应的值
                if(layEvent === 'del'){
                    layer.confirm('确认删除吗？', function(index){
                        $.post("{{ route('admin.permission.destroy') }}",{_method:'delete',ids:[data.id]},function (result) {
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
                        title:'编辑权限',
                        shadeClose:true, area: ['100%', '100%'],
                        content:  '/admin/permission/'+data.id+'/edit',
                        end:function () {
                            dataTable.reload();
                        }
                    });
                } else if (layEvent === 'children'){
                    $("#returnParent").show();
                    var pid = $("#returnParent").attr("pid");
                    if (data.parent_id!=0){
                        $("#returnParent").attr("pid",pid+'_'+data.parent_id);
                    }
                    dataTable.reload({
                        where:{model:"permission",parent_id:data.id},
                        page:{curr:1}
                    })
                }
            });

            //按钮批量删除
            $("#listDelete").click(function () {
                layer.msg("由于权限重要性，系统已禁止批量删除",{icon:5});
                /*var ids = []
                var hasCheck = table.checkStatus('dataTable')
                var hasCheckData = hasCheck.data
                if (hasCheckData.length>0){
                    $.each(hasCheckData,function (index,element) {
                        ids.push(element.id)
                    })
                }
                if (ids.length>0){
                    layer.confirm('确认删除吗？', function(index){
                        $.post("{{ route('admin.permission.destroy') }}",{_method:'delete',ids:ids},function (result) {
                            if (result.code==0){
                                dataTable.reload()
                            }
                            layer.close(index);
                            layer.msg(result.msg,{icon:6})
                        });
                    })
                }else {
                    layer.msg('请选择删除项',{icon:5})
                }*/
            });
            //返回上一级
            $("#returnParent").click(function () {
                var pid = $(this).attr("pid");
                if (pid!='0'){
                    ids = pid.split('_');
                    parent_id = ids.pop();
                    $(this).attr("pid",ids.join('_'));
                }else {
                    parent_id=pid;
                    $(this).hide();
                }
                dataTable.reload({
                    where:{model:"permission",parent_id:parent_id},
                    page:{curr:1}
                })
            })
        })
    </script>
    @endcan
@endsection