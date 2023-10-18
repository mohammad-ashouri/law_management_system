<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

//use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Mews\Captcha\Captcha;
use Jenssegers\Agent\Agent;

class LoginController extends Controller
{
//    use AuthenticatesUsers;

    protected $redirectTo = '/dashboard';

    public function getCaptcha(Captcha $captcha)
    {
        $captcha->builder()->build();
        session(['captcha' => $captcha->builder()->getPhrase()]);
        return $captcha->response();
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('web')->only('logout');
    }

    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        Auth::logout();
        return view('login');
    }

    public function login(Request $request)
    {

        if (!$request->input('username')) {
            $this->logActivity('Login Failed (Null Username)', request()->ip(), request()->userAgent());
            return response()->json([
                'success' => false,
                'errors' => [
                    'username' => ['نام کاربری وارد نشده است.']
                ]
            ]);
        } elseif (!$request->input('password')) {
            $this->logActivity('Failed Login With This Username (Null Password)', request()->ip(), request()->userAgent());
            return response()->json([
                'success' => false,
                'errors' => [
                    'password' => ['رمز عبور وارد نشده است.']
                ]
            ]);
        } elseif (!$request->input('captcha')) {
            $this->logActivity('Login Failed (Null Captcha) For User => ( ' . $request->input('username').' )', request()->ip(), request()->userAgent());
            return response()->json([
                'success' => false,
                'errors' => [
                    'captcha' => ['کد امنیتی وارد نشده است.']
                ]
            ]);
        }

        $captcha = $request->input('captcha');
        $sessionCaptcha = session('captcha')['key'];
        if (!password_verify($captcha, $sessionCaptcha)) {
            $this->logActivity('Login Failed (Wrong Captcha) For User => ( ' . $request->input('username') . ' )', request()->ip(), request()->userAgent());
            return response()->json([
                'success' => false,
                'errors' => [
                    'captcha' => ['کد امنیتی صحیح وارد نشده است.']
                ]
            ]);
        }

        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            $this->logActivity('Login With This Username => ' . $request->input('username'), request()->ip(), request()->userAgent());
            Session::put('username', $request->input('username'));
            $user = User::where('username', $request->input('username'))->first();
            $userID=$user['id'];
            Session::put('id', $userID);
            Session::put('type', $user['type']);
            return response()->json([
                'success' => true,
                'redirect' => route('dashboard')
            ]);
        }
        $this->logActivity('Login Failed (Wrong Username Or Password) For User => ( ' . $request->input('username') . ' )', request()->ip(), request()->userAgent());
        return response()->json([
            'success' => false,
            'errors' => [
                'loginError' => ['نام کاربری یا رمز عبور اشتباه است.']
            ]
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    protected function authenticated(Request $request, $user)
    {
        return redirect()->intended($this->redirectPath());
    }
}
