<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserCreateRequest $request)
    {
        $data = $request->all();
        $data['uuid'] = \Faker\Provider\Uuid::uuid();
        $data['password'] = bcrypt($data['password']);
        if (User::create($data)){
            return redirect()->to(route('admin.user'))->with(['status'=>'添加用户成功']);
        }
        return redirect()->to(route('admin.user'))->withErrors('系统错误');
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
        $user = User::findOrFail($id);
        return view('admin.user.edit',compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $data = $request->except('password');
        if ($request->get('password')){
            $data['password'] = bcrypt($request->get('password'));
        }
        if ($user->update($data)){
            return redirect()->to(route('admin.user'))->with(['status'=>'更新用户成功']);
        }
        return redirect()->to(route('admin.user'))->withErrors('系统错误');
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
        if (User::destroy($ids)){
            return response()->json(['code'=>0,'msg'=>'删除成功']);
        }
        return response()->json(['code'=>1,'msg'=>'删除失败']);
    }

    /**
     * 分配角色
     */
    public function role(Request $request,$id)
    {
        $user = User::findOrFail($id);
        $roles = Role::get();
        $hasRoles = $user->roles();
        foreach ($roles as $role){
            $role->own = $user->hasRole($role) ? true : false;
        }
        return view('admin.user.role',compact('roles','user'));
    }

    /**
     * 更新分配角色
     */
    public function assignRole(Request $request,$id)
    {
        $user = User::findOrFail($id);
        $roles = $request->get('roles',[]);
       if ($user->syncRoles($roles)){
           return redirect()->to(route('admin.user'))->with(['status'=>'更新用户角色成功']);
       }
        return redirect()->to(route('admin.user'))->withErrors('系统错误');
    }

    /**
     * 分配权限
     */
    public function permission(Request $request,$id)
    {
        $user = User::findOrFail($id);
        $permissions = $this->tree();
        foreach ($permissions as $key1 => $item1){
            $permissions[$key1]['own'] = $user->hasDirectPermission($item1['id']) ? 'checked' : false ;
            if (isset($item1['_child'])){
                foreach ($item1['_child'] as $key2 => $item2){
                    $permissions[$key1]['_child'][$key2]['own'] = $user->hasDirectPermission($item2['id']) ? 'checked' : false ;
                    if (isset($item2['_child'])){
                        foreach ($item2['_child'] as $key3 => $item3){
                            $permissions[$key1]['_child'][$key2]['_child'][$key3]['own'] = $user->hasDirectPermission($item3['id']) ? 'checked' : false ;
                        }
                    }
                }
            }
        }
        return view('admin.user.permission',compact('user','permissions'));
    }

    /**
     * 存储权限
     */
    public function assignPermission(Request $request,$id)
    {
        $user = User::findOrFail($id);
        $permissions = $request->get('permissions');

        if (empty($permissions)){
            $user->permissions()->detach();
            return redirect()->to(route('admin.user'))->with(['status'=>'已更新用户直接权限']);
        }
        $user->syncPermissions($permissions);
        return redirect()->to(route('admin.user'))->with(['status'=>'已更新用户直接权限']);
    }

}
