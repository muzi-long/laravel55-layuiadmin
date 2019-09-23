<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ConfigGroupRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use App\Models\ConfigGroup;
use Illuminate\Support\Facades\DB;

class ConfigGroupController extends Controller
{
    /**
     * 标签列表
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return View::make('admin.config_group.index');
    }

    /**
     * 标签数据接口
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function data(Request $request)
    {

        $res = ConfigGroup::orderBy('sort','asc')->orderBy('id','desc')->paginate($request->get('limit',30));
        $data = [
            'code' => 0,
            'msg'   => '正在请求中...',
            'count' => $res->total(),
            'data'  => $res->items(),
        ];
        return Response::json($data);
    }

    /**
     * 添加标签
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        return View::make('admin.config_group.create');
    }

    /**
     * 添加标签
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(ConfigGroupRequest $request)
    {
        $data = $request->all(['name','sort']);
        try{
            ConfigGroup::create($data);
            return Redirect::to(URL::route('admin.config_group'))->with(['success'=>'更新成功']);
        }catch (\Exception $exception){
            return Redirect::back()->withErrors('添加失败');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * 更新标签
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $configGroup = ConfigGroup::findOrFail($id);
        return View::make('admin.config_group.edit',compact('configGroup'));
    }

    /**
     * 更新标签
     * @param ConfigGroupRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ConfigGroupRequest $request, $id)
    {
        $configGroup = ConfigGroup::findOrFail($id);
        $data = $request->all(['name','sort']);
        try{
            $configGroup->update($data);
            return Redirect::to(URL::route('admin.config_group'))->with(['success'=>'更新成功']);
        }catch (\Exception $exception){
            return Redirect::back()->withErrors('更新失败');
        }
    }

    /**
     * 删除标签
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        $ids = $request->get('ids');
        if (!is_array($ids) || empty($ids)){
            return Response::json(['code'=>1,'msg'=>'请选择删除项']);
        }
        $group = ConfigGroup::with('configurations')->find($ids[0]);
        if ($group->configurations->isNotEmpty()){
            return Response::json(['code'=>1,'msg'=>'该组存在配置项，禁止删除']);
        }
        try{
            $group->delete();
            return Response::json(['code'=>0,'msg'=>'删除成功']);
        }catch (\Exception $exception){
            return Response::json(['code'=>1,'msg'=>'删除失败','data'=>$exception->getMessage()]);
        }
    }
}
