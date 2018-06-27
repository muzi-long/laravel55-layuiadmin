@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <div class="layui-form">
                <div class="layui-input-inline">
                    <select name="user_type" lay-verify="required" lay-filter="type">
                        <option value="2">后台用户</option>
                        <option value="3">前台用户</option>
                    </select>
                </div>
                <div class="layui-input-inline">
                    <input type="text" name="keywords" placeholder="请输入用户名或手机号码" class="layui-input">
                </div>
                <div class="layui-input-inline">
                    <button type="button" class="layui-btn" id="search">搜索</button>
                    <button type="button" class="layui-btn" id="chioceUser">确定</button>
                </div>
            </div>
        </div>
        <div class="layui-card-body">
            <table id="dataTable" lay-filter="dataTable"></table>
        </div>
    </div>
@endsection

@section('script')
    <script>
        layui.use(['layer','table','form'],function () {
            var layer = layui.layer;
            var form = layui.form;
            var table = layui.table;
            //用户表格初始化
            var dataTable = table.render({
                elem: '#dataTable'
                ,height: 300
                ,url: "{{route('admin.message.getUser')}}" //数据接口
                ,page: true //开启分页
                ,cols: [[ //表头
                    {checkbox: true,fixed: true}
                    ,{field: 'id', title: 'ID', sort: true,width:80}
                    ,{field: 'name', title: '用户名'}
                    ,{field: 'phone', title: '电话'}
                ]]
            });
            //搜索
            $("#search").click(function () {
                var keywords = $("input[name='keywords']").val();
                var user_type = $("select[name='user_type']").val();
                dataTable.reload({
                    page:{curr:1},
                    where:{keywords:keywords,user_type:user_type}
                })
            })

            //监听select选择
            form.on('select(type)', function(data){
                dataTable.reload({
                    page:{curr:1},
                    where:{user_type:data.value}
                });
            });

            //选择用户
            $("#chioceUser").click(function () {
                var hasCheck = table.checkStatus('dataTable')
                if (hasCheck.data.length>0){
                    var type = $("select[name='user_type']").val();
                    var obj = parent.$(".userBox"+type);
                    $.each(hasCheck.data,function (index,item) {
                        if (obj.find("#"+item.uuid).length<=0){
                            var html ='<li id="'+item.uuid+'" class="li'+type+'">'+item.name+'<i title="移除" onclick="removeLi(this)">&times;</i><input type="hidden" name="user['+type+'][]" value="'+item.uuid+'"></li>'
                            obj.append(html)
                        }
                    })
                    layer.msg('添加完成')
                }
            })
        })
    </script>
@endsection