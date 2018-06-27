<?php

namespace App\Http\Controllers\Home;

use App\Models\Member;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
{
    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest:member')->except(['index','logout']);
    }

    public function index()
    {
        return '会员主页登录后才能访问'.auth('member')->user()->name.auth('member')->user()->phone;
    }

    //注册表单
    public function showRegisterForm()
    {
        return view('home.member.register');
    }
    //注册
    public function register(Request $request)
    {
        $this->validate($request,[
            'captcha' => 'required|captcha',
            'name' => 'required',
            'phone' => 'required|numeric|regex:/^1[3456789][0-9]{9}$/|unique:members',
            'password'  => 'required|confirmed|min:6|max:14'
        ],[
            'captcha.captcha' => '验证码错误'
        ]);
        $data = array_merge($request->all(),['uuid'=>\Faker\Provider\Uuid::uuid()]);
        if ($member = Member::create($data)){
            $this->guard()->login($member);
        }
        return back()->with(['status'=>'系统错误']);
    }

    //登录表单
    public function showLoginForm()
    {
        return view('home.member.login');
    }

    public function redirectTo()
    {
        return route('home.member');
    }

    /**
     * @param Request $request
     * 登录验证
     */
    public function validateLogin(Request $request)
    {
        $this->validate($request, [
            'captcha' => 'required|captcha',
            $this->username() => 'required|regex:/^1[3456789][0-9]{9}$/',
            'password' => 'required|string',
        ],[
            'captcha.captcha'=>'图形验证码错误',
        ]);
    }

    /**
     * @return string
     * 登录验证的字段
     */
    public function username()
    {
        return 'phone';
    }

    //注销、退出
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect('/');
    }

    protected function guard()
    {
        return Auth::guard('member');
    }

}
