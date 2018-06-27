<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PermissionCreateRequest;
use App\Http\Requests\PermissionUpdateRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('admin.permission.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = $this->tree();
        return view('admin.permission.create',compact('permissions'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PermissionCreateRequest $request)
    {
        $data = $request->all();
        if (Permission::create($data)){
            return redirect()->to(route('admin.permission'))->with(['status'=>'添加成功']);
        }
        return redirect()->to(route('admin.permission'))->withErrors('系统错误');
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $permission = Permission::findOrFail($id);
        $permissions = $this->tree();
        return view('admin.permission.edit',compact('permission','permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PermissionUpdateRequest $request, $id)
    {
        $permission = Permission::findOrFail($id);
        $data = $request->all();
        if ($permission->update($data)){
            return redirect()->to(route('admin.permission'))->with(['status'=>'更新权限成功']);
        }
        return redirect()->to(route('admin.permission'))->withErrors('系统错误');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $ids = $request->get('ids');
        if (empty($ids)){
            return response()->json(['code'=>1,'msg'=>'请选择删除项']);
        }
        $permission = Permission::find($ids[0]);
        if (!$permission){
            return response()->json(['code'=>-1,'msg'=>'权限不存在']);
        }
        //如果有子权限，则禁止删除
        if (Permission::where('parent_id',$ids[0])->first()){
            return response()->json(['code'=>2,'msg'=>'存在子权限禁止删除']);
        }

        if ($permission->delete()){
            return response()->json(['code'=>0,'msg'=>'删除成功']);
        }
        return response()->json(['code'=>1,'msg'=>'删除失败']);
    }
}
