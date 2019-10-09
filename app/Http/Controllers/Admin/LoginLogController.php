<?php

namespace App\Http\Controllers\Admin;

use App\Models\LoginLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoginLogController extends Controller
{
    /**
     * 列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if ($request->ajax()){
            $model = LoginLog::query();
            $data = $request->all(['username','name','created_start_at','created_end_at']);
            if ($data['username']){
                $model = $model->where('username','like','%'.$data['username'].'%');
            }
            if ($data['name']){
                $model = $model->where('name','like','%'.$data['name'].'%');
            }
            if ($data['created_start_at'] && !$data['created_end_at']){
                $model = $model->where('created_at','>=',$data['create_end_at']);
            }elseif (!$data['created_start_at'] && $data['created_end_at']){
                $model = $model->where('created_at','<=',$data['create_end_at']);
            }elseif ($data['created_start_at'] && $data['created_end_at']){
                $model = $model->whereBetween('created_at',[$data['create_start_at'],$data['create_end_at']]);
            }
            $res = $model->orderByDesc('id')->paginate($request->get('limit', 30))->toArray();
            $data = [
                'code' => 0,
                'msg' => '正在请求中...',
                'count' => $res['total'],
                'data' => $res['data']
            ];
            return response()->json($data);
        }

        return view('admin.login_log.index',compact('logs','data'));
    }

    /**
     * 删除
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        $ids = $request->get('ids');
        if (!is_array($ids)||empty($ids)){
            return response()->json(['code'=>1,'msg'=>'请选择删除项']);
        }
        if (LoginLog::destroy($ids)){
            return response()->json(['code'=>0,'msg'=>'删除成功']);
        }
        return response()->json(['code'=>0,'msg'=>'删除失败']);
    }
}
