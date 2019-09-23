<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\Configuration;
use App\Models\LoginLog;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{

    use AuthenticatesUsers;

    /**
     * 用户登录表单
     * @return \Illuminate\Contracts\View\View
     */
    public function showLoginForm()
    {
        return View::make('admin.user.login');
    }

    /**
     * 验证登录字段
     * @param Request $request
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            'captcha' => 'required|captcha',
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }

    //登录成功后的跳转地址
    public function redirectTo()
    {
        return URL::route('admin.layout');
    }

    /**
     * 退出后的动作
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function loggedOut(Request $request)
    {
        return Redirect::to(URL::route('admin.user.login'));
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }

    protected function authenticated(Request $request, $user)
    {
        //缓存配置信息
        $configuration = Configuration::pluck('val','key');
        $request->session()->put('configuration',$configuration);
        //记录登录成功日志
        if ($configuration['login_log']==1){
            LoginLog::create([
                'username' => $user->username,
                'ip' => $request->ip(),
                'method' => $request->method(),
                'user_agent' => $request->header('User-Agent'),
                'remark' => '登录成功',
            ]);
        }
    }

    /**
     * 用于登录的字段
     * @return string
     */
    public function username()
    {
        return 'username';
    }

    /**
     * 更改密码
     * @return \Illuminate\Contracts\View\View
     */
    public function changeMyPasswordForm()
    {
        return View::make('admin.user.changeMyPassword');
    }

    /**
     * 修改密码
     * @param ChangePasswordRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeMyPassword(ChangePasswordRequest $request)
    {
        $data = $request->all(['old_password','new_password']);
        //验证原密码
        if (!Hash::check($data['old_password'],$request->user()->getAuthPassword())){
            return Redirect::back()->withErrors('原密码不正确');
        }
        try{
            $request->user()->fill(['password' => $data['new_password']])->save();
            return Redirect::back()->with(['success'=>'密码修改成功']);
        }catch (\Exception $exception){
            return Redirect::back()->withErrors('修改失败');
        }
    }

    /**
     * 用户列表主页
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return View::make('admin.user.index');
    }

    public function data(Request $request)
    {
        $res = User::paginate($request->get('limit', 30));
        $data = [
            'code' => 0,
            'msg' => '正在请求中...',
            'count' => $res->total(),
            'data' => $res->items()
        ];
        return Response::json($data);
    }

    /**
     * 添加用户
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        return View::make('admin.user.create');
    }

    /**
     * 添加用户
     * @param UserCreateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UserCreateRequest $request)
    {
        $data = $request->all();
        try{
            User::create($data);
            return Redirect::to(URL::route('admin.user'))->with(['success'=>'添加成功']);
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
     * 更新用户
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return View::make('admin.user.edit',compact('user'));
    }

    /**
     * 更新用户
     * @param UserUpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UserUpdateRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $data = $request->except('password');
        if ($request->get('password')){
            $data['password'] = $request->get('password');
        }
        try{
            $user->update($data);
            return Redirect::to(URL::route('admin.user'))->with(['success'=>'更新成功']);
        }catch (\Exception $exception){
            return Redirect::back()->withErrors('更新失败');
        }
    }

    /**
     * 删除用户
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
            User::destroy($ids);
            return Response::json(['code'=>0,'msg'=>'删除成功']);
        }catch (\Exception $exception){
            return Response::json(['code'=>1,'msg'=>'删除失败']);
        }
    }

    /**
     * 分配角色
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function role($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::get();
        foreach ($roles as $role){
            $role->own = $user->hasRole($role) ? true : false;
        }
        return View::make('admin.user.role',compact('roles','user'));
    }

    /**
     * 分配角色
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function assignRole(Request $request,$id)
    {
        $user = User::findOrFail($id);
        $roles = $request->get('roles',[]);
        try{
            $user->syncRoles($roles);
            return Redirect::to(URL::route('admin.user'))->with(['success'=>'更新成功']);
        }catch (\Exception $exception){
            return Redirect::back()->withErrors('更新失败');
        }
    }

    /**
     * 分配直接权限
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function permission($id)
    {
        $user = User::findOrFail($id);
        $permissions = Permission::with('allChilds')->where('parent_id',0)->get();
        foreach ($permissions as $p1){
            $p1->own = $user->hasDirectPermission($p1->id) ? 'checked' : '' ;
            if ($p1->childs->isNotEmpty()){
                foreach ($p1->childs as $p2){
                    $p2->own = $user->hasDirectPermission($p2->id) ? 'checked' : '' ;
                    if ($p2->childs->isNotEmpty()){
                        foreach ($p2->childs as $p3){
                            $p3->own = $user->hasDirectPermission($p3->id) ? 'checked' : '' ;
                        }
                    }
                }
            }
        }
        return View::make('admin.user.permission',compact('user','permissions'));
    }

    /**
     * 分配直接权限
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function assignPermission(Request $request,$id)
    {
        $user = User::findOrFail($id);
        $permissions = $request->get('permissions',[]);
        try{
            $user->syncPermissions($permissions);
            return Redirect::to(URL::route('admin.user'))->with(['success'=>'更新成功']);
        }catch (\Exception $exception){
            return Redirect::back()->withErrors('更新失败');
        }
    }

}
