<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\RoleCreateRequest;
use App\Http\Requests\RoleUpdateRequest;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;

class RoleController extends Controller
{
    /**
     * 角色列表
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return View::make('admin.role.index');
    }

    /**
     * 角色列表接口数据
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function data(Request $request)
    {
        $res = Role::paginate($request->get('limit', 30));
        $data = [
            'code' => 0,
            'msg' => '正在请求中...',
            'count' => $res->total(),
            'data' => $res->items()
        ];
        return Response::json($data);
    }

    /**
     * 添加角色
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        return View::make('admin.role.create');
    }

    /**
     * 添加角色
     * @param RoleCreateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(RoleCreateRequest $request)
    {
        $data = $request->only(['name','display_name']);
        try{
            Role::create($data);
            return Redirect::to(URL::route('admin.role'))->with(['success'=>'添加成功']);
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
     * 更新角色
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        return View::make('admin.role.edit',compact('role'));
    }

    /**
     * 更新角色
     * @param RoleUpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(RoleUpdateRequest $request, $id)
    {
        $role = Role::findOrFail($id);
        $data = $request->only(['name','display_name']);
        try{
            $role->update($data);
            return Redirect::to(URL::route('admin.role'))->with(['success'=>'更新成功']);
        }catch (\Exception $exception){
            return Redirect::back()->withErrors('更新失败');
        }
    }

    /**
     * 删除角色
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        $ids = $request->get('ids');
        if (!is_array($ids) || empty($ids)){
            return Response::json(['code'=>1,'msg'=>'请选择删除项']);
        }
        try{
            Role::destroy($ids);
            return Response::json(['code'=>0,'msg'=>'删除成功']);
        }catch (\Exception $exception){
            return Response::json(['code'=>1,'msg'=>'删除失败']);
        }
    }

    /**
     * 分配权限
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function permission(Request $request,$id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::with('allChilds')->where('parent_id',0)->get();
        foreach ($permissions as $p1){
            $p1->own = $role->hasPermissionTo($p1->id) ? 'checked' : false ;
            if ($p1->childs->isNotEmpty()){
                foreach ($p1->childs as $p2){
                    $p2->own = $role->hasPermissionTo($p2->id) ? 'checked' : false ;
                    if ($p2->childs->isNotEmpty()){
                        foreach ($p2->childs as $p3){
                            $p3->own = $role->hasPermissionTo($p3->id) ? 'checked' : false ;
                        }
                    }
                }
            }
        }
        return View::make('admin.role.permission',compact('role','permissions'));
    }

    /**
     * 存储权限
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function assignPermission(Request $request,$id)
    {
        $role = Role::findOrFail($id);
        $permissions = $request->get('permissions',[]);
        try{
            $role->syncPermissions($permissions);
            return Redirect::to(URL::route('admin.role'))->with(['success'=>'更新成功']);
        }catch (\Exception $exception){
            return Redirect::back()->withErrors('更新失败');
        }
    }
}
