<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Morilog\Jalali\Jalalian;

class DashboardController extends Controller
{
    public function Profile(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('Profile');
    }

    public function ChangePasswordInc(Request $request): \Illuminate\Http\JsonResponse
    {
        $oldPass = $request->input('oldPass');
        $newPass = $request->input('newPass');
        $repeatNewPass = $request->input('repeatNewPass');
        $password = User::where('username', session('username'))->value('password');
        if (!$oldPass) {
            return $this->alerts(false, 'oldPassNull', 'رمز عبور فعلی وارد نشده است');
        } elseif (!$newPass) {
            return $this->alerts(false, 'newPassNull', 'رمز عبور جدید وارد نشده است');
        } elseif (strlen($newPass) < 8 and $newPass) {
            return $this->alerts(false, 'lowerThan8', 'رمز عبور جدید کمتر از 8 کاراکتر وارد شده است');
        } elseif (strlen($newPass) > 12 and $newPass) {
            return $this->alerts(false, 'higherThan12', 'رمز عبور جدید بیشتر از 12 کاراکتر وارد شده است');
        } elseif (!$repeatNewPass) {
            return $this->alerts(false, 'repeatNewPassNull', 'تکرار رمز عبور جدید وارد نشده است');
        } elseif ($newPass !== $repeatNewPass) {
            return $this->alerts(false, 'wrongRepeat', 'عدم تطابق رمز عبور جدید و تکرار آن');
        } elseif (!password_verify($oldPass, $password)) {
            return $this->alerts(false, 'wrongPassword', 'رمز عبور فعلی اشتباه است.');
        } else {
            $user = User::where('username', session('username'))->first();
            $user->password = bcrypt($newPass);
            $user->NTCP = 0;
            $user->save();
            $this->logActivity('Password Changed', request()->ip(), request()->userAgent(), session('id'));
            return $this->alerts(true, 'passwordChanged', 'رمز عبور با موفقیت تغییر کرد!');
        }
    }

    public function ChangeUserImage(Request $request)
    {
        $file_src = $request->file('image');
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpg,png,jpeg,bmp|max:5120',
        ]);
        if ($validator->fails()) {
            return $this->alerts(false, 'wrongImage', 'فایل نامعتبر انتخاب شده است.');
        }
        if ($file_src) {
            $folderName = str_replace(array('/', '\\'), '', bcrypt($file_src->getClientOriginalName()));
            $postFilePath = $file_src->storeAs('public/UserImages/' . $folderName, $file_src->getClientOriginalName());
            if ($postFilePath){
                $user=User::find(session('id'));
                $user->user_image=$postFilePath;
                $user->save();
                return $this->alerts(true, 'imageChanged', 'رمز عبور با موفقیت تغییر کرد!');
            }
        } else {
            return $this->alerts(false, 'wrongImage', 'فایل اثر انتخاب نشده است');
        }
    }

    public function jalaliDateAndTime(): string
    {
        $data = Jalalian::forge('today')->format('%A, %d %B %Y');
        return $data . ' ' . date("h:i");
    }

    public function jalaliDate(): string
    {
        return Jalalian::now()->format('Y/m/d');
    }

    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('dashboard');
    }
}
