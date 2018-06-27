<?php

namespace App\Http\Controllers\Admin;

use App\Models\Icon;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    /**
     * 后台布局
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function layout()
    {
        return view('admin.layout');
    }

    /**
     * 后台首页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.index.index');
    }
    public function index1()
    {
        return view('admin.index.index1');
    }
    public function index2()
    {
        return view('admin.index.index2');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * 数据表格接口
     */
    public function data(Request $request)
    {
        $model = $request->get('model');
        switch (strtolower($model)) {
            case 'user':
                $query = new User();
                break;
            case 'role':
                $query = new Role();
                break;
            case 'permission':
                $query = new Permission();
                $query = $query->where('parent_id', $request->get('parent_id', 0))->with('icon');
                break;
            default:
                $query = new User();
                break;
        }
        $res = $query->paginate($request->get('limit', 30))->toArray();
        $data = [
            'code' => 0,
            'msg' => '正在请求中...',
            'count' => $res['total'],
            'data' => $res['data']
        ];
        return response()->json($data);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * 所有icon图标
     */
    public function icons()
    {
        $icons = Icon::orderBy('sort', 'desc')->get();
        return response()->json(['code' => 0, 'msg' => '请求成功', 'data' => $icons]);
    }

}
