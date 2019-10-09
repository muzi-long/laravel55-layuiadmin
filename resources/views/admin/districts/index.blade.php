@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <div class="layui-btn-group">
                 <button class="layui-btn layui-btn-sm" id="returnParent" pid="0">返回上级</button>
            </div>
        </div>
        <div class="layui-card-body">
            <table id="dataTable" lay-filter="dataTable" ></table>
            <script type="text/html" id="options">
                <div class="layui-btn-group">
                    @can('districts.dislist')
                        <a class="layui-btn layui-btn-sm" lay-event="children">子分类</a>
                    @endcan
                    @can('districts.dislist.edit')
                        @{{# if(d.enable == '0'){ }}
                            <a class="layui-btn layui-btn-sm" lay-event="enabled">启用</a>
                        @{{# }else if(d.enable == '1'){ }}
                            <a class="layui-btn layui-btn-sm layui-btn-danger" lay-event="enabled">禁用</a>
                        @{{# } }}
                    @endcan


                </div>
            </script>
        </div>
    </div>
@endsection

@section('script')
    @can('districts.dislist')
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
                    ,url: "{{ route('admin.dislist.data') }}" //数据接口
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
                        ,{field: 'name', title: '地区名称',edit:'text'}
                        ,{field: 'adcode', title: '行政编码'}
                        ,{field: 'center', title: '经纬度',width:180}
                        ,{field: 'parent_id', title: '父级ID'}
                        ,{field: 'sort',sort: true, title: '排序',edit:'text'}
                        ,{field: 'enable', title: '是否启用',align:'center',templet:function(d){
                            if(d.enable == '0'){
                                return "禁用"
                            }else if(d.enable =='1'){
                                return "启用"
                            }
                        }}
                        ,{field: 'created_at', title: '创建时间'}
                        ,{field: 'updated_at', title: '更新时间'}
                        ,{fixed: 'right', width: 320, align:'center', toolbar: '#options'}
                    ]]
                });

                //监听工具条
                table.on('tool(dataTable)', function(obj){ //注：tool是工具条事件名，dataTable是table原始容器的属性 lay-filter="对应的值"
                    var _data = obj.data //获得当前行数据
                        ,layEvent = obj.event; //获得 lay-event 对应的值
                    if(layEvent === 'enabled'){
                        var content = _data.enable === 1?"是否确认禁用该地区？禁用后前台将无法使用该地区。":"是否确认启用该地区？";
                        layer.confirm(content, function(index){
                            var index = layer.load();
                            var url ="{{route('admin.dislist.status')}}";
                            var data = {
                                "id"     : _data.id,
                                "field" : "enable",
                                "value" : _data.enable === 1?0:1,
                                "_method" : "put"
                            }
                            $.post(url,data,function (res) {
                                layer.close(index)
                                layer.msg(res.msg)
                                dataTable.reload();
                            },'json');
                        });
                    }  else if (layEvent === 'children'){
                        var pid = $("#returnParent").attr("pid");
                        if (_data.parent_id!=0){
                            $("#returnParent").attr("pid",pid+'_'+_data.parent_id);
                        }
                        dataTable.reload({
                            where:{model:"permission",parent_id:_data.id},
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

                table.on('edit(dataTable)', function(obj){
                    var value = obj.value //得到修改后的值
                        ,data = obj.data //得到所在行所有键值
                        ,field = obj.field; //得到字段
                    var reg = /^[0-9]{1,2}$/;
                    if(field == 'sort' && !reg.test(value)){
                        layer.msg("请输入正确的排序编号！！",{icon:5});
                        dataTable.reload();
                        return false;
                    }
                    $.post("{{route('admin.dislist.change')}}",{_method:'put','id':data.id,'field':field,'value':value},function(result){
                        dataTable.reload();
                    })
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