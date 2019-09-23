<?php

namespace App\Http\Controllers\Admin;

use App\Models\Configuration;
use App\Models\OperateLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;

class OperateLogController extends Controller
{
    /**
     * 日志主页
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return View::make('admin.log.operate');
    }

    /**
     * 数据接口
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function data(Request $request)
    {
        $data = $request->all(['created_at_start','created_at_end']);
        $res = OperateLog::when($data['created_at_start']&&!$data['created_at_end'],function ($query,$data){
                return $query->where('created_at','>=',$data['created_at_start']);
            })->when(!$data['created_at_start']&&$data['created_at_end'],function ($query,$data){
                return $query->where('created_at','<=',$data['created_at_end']);
            })->when($data['created_at_start']&&$data['created_at_end'],function ($query,$data){
                return $query->whereBetween('created_at',[$data['created_at_start'],$data['created_at_end']]);
            })->orderBy('id','desc')->paginate($request->get('limit',30));
        $data = [
            'code' => 0,
            'msg'   => '正在请求中...',
            'count' => $res->total(),
            'data'  => $res->items(),
        ];
        return Response::json($data);
    }

    /**
     * 删除
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        $ids = $request->get('ids');
        if (!is_array($ids) || empty($ids)){
            return Response::json(['code'=>1,'msg'=>'请选择删除项']);
        }
        //查询配置是否允许删除 0-禁止，1-允许
        $configuration = Configuration::where('key','delete_operate_log')->where('val',1)->first();
        if ($configuration==null){
            return Response::json(['code'=>1,'msg'=>'系统已设置禁止删除操作日志']);
        }
        try{
            OperateLog::destroy($ids);
            return Response::json(['code'=>0,'msg'=>'删除成功']);
        }catch (\Exception $exception){
            return Response::json(['code'=>1,'msg'=>'删除失败','data'=>$exception->getMessage()]);
        }
    }
}
