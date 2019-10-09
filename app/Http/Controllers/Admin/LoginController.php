<?php

namespace App\Http\Controllers\Admin;
use App\Models\Site;
use App\Models\LoginLog;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * 登录表单
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showLoginForm()
    {
        $config = Site::pluck('value','key');
        return view('admin.login_register.login',compact('config',$config));
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
     * 登录成功后的跳转地址
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function redirectTo()
    {
        return route('admin.layout');
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect(route('admin.login'));
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
    protected function validateLogin(Request $request){
        $this->validate($request, [
            $this->username() => 'required|string',
            'password' => 'required|string',
            'captcha' => 'required|captcha',
        ],[
            'captcha.required' => '请填写验证码',
            'captcha.captcha' => '验证码错误',
        ]);
    }

    protected function authenticated(Request $request, $user)
    {
        $data = [
            'user_id' => $user->id,
            'username' => $user->username,
            'realname' => $user->realname,
            'ip' => $request->getClientIp()
        ];
        LoginLog::create($data);
    }

}
