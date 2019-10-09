@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <div class="layui-btn-group">
                @can('zixun.category.create')
                    <button class="layui-btn layui-btn-sm" id="add_category" pid="0">添 加</button>
                @endcan
                    <button class="layui-btn layui-btn-sm" id="returnParent" pid="0">返回上级</button>
            </div>
        </div>
        <div class="layui-card-body">
            <table id="dataTable" lay-filter="dataTable"></table>
            <script type="text/html" id="options">
                <div class="layui-btn-group">
                    @can('zixun.category')
                        <a class="layui-btn layui-btn-sm" lay-event="children">子分类</a>
                    @endcan
                    @can('zixun.category.edit')
                        <a class="layui-btn layui-btn-sm" lay-event="edit">编辑</a>
                    @endcan
                    @can('zixun.category.destroy')
                        <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del">删除</a>
                    @endcan
                </div>
            </script>
        </div>
    </div>
@endsection

@section('script')
    @can('zixun.category')
        <script>
            layui.use(['layer','table','form'],function () {
                var layer = layui.layer;
                var form = layui.form;
                var table = layui.table;
                //用户表格初始化
                var dataTable = table.render({
                    elem: '#dataTable'
                    ,autoSort: false
                    ,height: 500
                    ,url: "{{ route('admin.category.data') }}" //数据接口
                    ,page: true //开启分页
                    ,done: function(res, curr, count){
                        //接口回调，处理一些和表格相关的辅助事项
                        if(res.data.length==0 && count>0){
                            dataTable.reload({
                                page: {
                                    curr: 1 //重新从第 1 页开始
                                }
                            });
                        }
                    }
                    ,cols: [[ //表头
                        {field: 'id', title: 'ID', sort: true,width:80}
                        ,{field: 'name', title: '分类名称'}
                        ,{field: 'sort',sort: true, title: '排序'}
                        ,{field: 'created_at', title: '创建时间'}
                        ,{field: 'updated_at', title: '更新时间'}
                        ,{fixed: 'right', width: 320, align:'center', toolbar: '#options'}
                    ]]
                });

                $('#add_category').on('click',function () {
                    var pid = $(this).attr("pid");
                    if (pid!='0'){
                        ids = pid.split('_');
                        parent_id = ids.pop();
                        $(this).attr("pid",ids.join('_'));
                        $('#add_category').attr("pid",parent_id);
                    }else {
                        parent_id=pid;
                        $('#add_category').attr("pid",parent_id);
                    }

                    layer.open({
                        type: 2,
                        shadeClose:true, area: ['100%', '100%'],
                        content: "{{ route('admin.category.create') }}"+"/"+parent_id,
                        end:function () {
                            dataTable.reload();
                        }
                    });
                });

                //监听工具条
                table.on('tool(dataTable)', function(obj){ //注：tool是工具条事件名，dataTable是table原始容器的属性 lay-filter="对应的值"
                    var data = obj.data //获得当前行数据
                        ,layEvent = obj.event; //获得 lay-event 对应的值
                    if(layEvent === 'del'){
                        layer.confirm('确认删除吗？', function(index){
                            $.post("{{ route('admin.category.destroy') }}",{_method:'delete',ids:data.id},function (result) {
                                if (result.code==0){
                                    obj.del(); //删除对应行（tr）的DOM结构
                                }
                                layer.close(index);
                                layer.msg(result.msg);
                                dataTable.reload();
                            });
                        });
                    } else if(layEvent === 'edit'){
                        layer.open({
                            type: 2,
                            shadeClose:true, area: ['100%', '100%'],
                            content: '/admin/category/'+data.id+'/edit',
                            end:function () {
                                dataTable.reload();
                            }
                        });
                    } else if (layEvent === 'children'){
                        var pid = $("#returnParent").attr("pid");
                        if (data.parent_id!=0){
                            $("#returnParent").attr("pid",pid+'_'+data.parent_id);
                        }
                        $("#add_category").attr("pid",data.id);
                        dataTable.reload({
                            where:{model:"permission",parent_id:data.id},
                            page:{curr:1}
                        })
                    }
                });

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

                //返回上一级
                $("#returnParent").click(function () {
                    var pid = $(this).attr("pid");
                    if (pid!='0'){
                        ids = pid.split('_');
                        parent_id = ids.pop();
                        $(this).attr("pid",ids.join('_'));
                        $('#add_category').attr("pid",parent_id);
                    }else {
                        parent_id=pid;
                        $('#add_category').attr("pid",pid);
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