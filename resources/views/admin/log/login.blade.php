@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <form class="layui-form">
                <div class="layui-btn-group ">
                    @can('system.login_log.destroy')
                        <button type="button" class="layui-btn layui-btn-sm layui-btn-danger" id="listDelete">删 除</button>
                    @endcan
                        <button type="button" class="layui-btn layui-btn-sm" lay-submit lay-filter="search" >搜 索</button>
                </div>
                <div class="layui-input-block">
                    <div class="layui-inline">
                        <label for="" class="layui-form-label">登录时间</label>
                        <div class="layui-input-inline">
                            <input type="text" name="created_at_start" id="created_at_start" placeholder="开始时间" readonly class="layui-input">
                        </div>
                        <div class="layui-form-mid layui-word-aux">-</div>
                        <div class="layui-input-inline">
                            <input type="text" name="created_at_end" id="created_at_end" placeholder="结束时间" readonly class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label for="" class="layui-form-label">用户名</label>
                        <div class="layui-input-inline">
                            <input type="text" name="username" placeholder="输入用户名" class="layui-input">
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="layui-card-body">
            <table id="dataTable" lay-filter="dataTable"></table>
            <script type="text/html" id="options">
                <div class="layui-btn-group">
                    @can('system.login_log.destroy')
                        <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del">删除</a>
                    @endcan
                </div>
            </script>
        </div>
    </div>
@endsection

@section('script')
    <script>
        layui.use(['layer', 'table', 'form','laydate'], function () {
            var $ = layui.jquery;
            var layer = layui.layer;
            var form = layui.form;
            var table = layui.table;
            var laydate = layui.laydate;
            laydate.render({elem:'#created_at_start',type:'datetime'});
            laydate.render({elem:'#created_at_end',type:'datetime'});
            //用户表格初始化
            var dataTable = table.render({
                elem: '#dataTable'
                , autoSort: false
                , height: 500
                , url: "{{ route('admin.login_log.data') }}" //数据接口
                , page: true //开启分页
                , cols: [[ //表头
                    {checkbox: true, fixed: true}
                    , {field: 'id', title: 'ID', sort: true, width: 80}
                    , {field: 'username', title: '用户名'}
                    , {field: 'ip', sort: true, title: 'ip地址'}
                    , {field: 'method', sort: true, title: '请求方式'}
                    , {field: 'user_agent', sort: true, title: 'UserAgent'}
                    , {field: 'created_at', title: '登录时间'}
                    , {fixed: 'right', align: 'center', toolbar: '#options'}
                ]]
            });

            //监听工具条
            table.on('tool(dataTable)', function (obj) { //注：tool是工具条事件名，dataTable是table原始容器的属性 lay-filter="对应的值"
                var data = obj.data //获得当前行数据
                    , layEvent = obj.event; //获得 lay-event 对应的值
                if (layEvent === 'del') {
                    layer.confirm('确认删除吗？', function (index) {
                        layer.close(index);
                        var load = layer.load();
                        $.post("{{ route('admin.login_log.destroy') }}", {
                            _method: 'delete',
                            ids: [data.id]
                        }, function (res) {
                            layer.close(load);
                            if (res.code == 0) {
                                layer.msg(res.msg, {icon: 1}, function () {
                                    obj.del();
                                })
                            } else {
                                layer.msg(res.msg, {icon: 2})
                            }
                        });
                    });
                }
            });

            //按钮批量删除
            $("#listDelete").click(function () {
                var ids = [];
                var hasCheck = table.checkStatus('dataTable');
                var hasCheckData = hasCheck.data;
                if (hasCheckData.length > 0) {
                    $.each(hasCheckData, function (index, element) {
                        ids.push(element.id)
                    })
                }
                if (ids.length > 0) {
                    layer.confirm('确认删除吗？', function (index) {
                        layer.close(index);
                        var load = layer.load();
                        $.post("{{ route('admin.login_log.destroy') }}", {
                            _method: 'delete',
                            ids: ids
                        }, function (res) {
                            layer.close(load);
                            if (res.code == 0) {
                                layer.msg(res.msg, {icon: 1}, function () {
                                    dataTable.reload({page: {curr: 1}});
                                })
                            } else {
                                layer.msg(res.msg, {icon: 2})
                            }
                        });
                    })
                } else {
                    layer.msg('请选择删除项')
                }
            })

            //搜索
            form.on('submit(search)',function (data) {
                dataTable.reload({
                    where:data.field,
                    page:{cur:1}
                });
                return false;
            })
        })
    </script>
@endsection