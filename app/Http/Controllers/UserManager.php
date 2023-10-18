<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserManager extends Controller
{

    public function ChangeUserActivationStatus(Request $request)
    {
        $username = $request->input('username');
        $user = User::where('username', $username)->first();
        if ($username and $user) {
            $userStatus = User::where('username', $username)->value('active');
            if ($userStatus == 1) {
                $status = 0;
                $subject = 'Deactivated';
                $subject2 = 'غیرفعال';
            } elseif ($userStatus == 0) {
                $status = 1;
                $subject = 'Activated';
                $subject2 = 'فعال';
            }

            $user->active = $status;
            $user->save();
            $this->logActivity('User => ' . $username . ' ' . $subject, request()->ip(), request()->userAgent(), session('id'));
            return $this->success(true, 'changedUserActivation', 'کاربر با موفقیت ' . $subject2 . ' شد.');
        } else {
            return $this->alerts(false, 'changedUserActivationFailed', 'خطا در انجام عملیات');
        }
    }

    public function ChangeUserNTCP(Request $request)
    {

        $username = $request->input('username');
        $user = User::where('username', $username)->first();
        if ($username and $user) {
            $userNTCP = User::where('username', $username)->value('NTCP');
            if ($userNTCP == 1) {
                $status = 0;
                $subject = 'NTCP';
            } elseif ($userNTCP == 0) {
                $status = 1;
                $subject = 'NNTCP';
            }

            $user->NTCP = $status;
            $user->save();
            $this->logActivity('User => ' . $username . ' ' . $subject, request()->ip(), request()->userAgent(), session('id'));
            return $this->success(true, 'changedUserNTCP', 'عملیات با موفقیت انجام شد.');
        } else {
            return $this->alerts(false, 'changedUserNTCPFailed', 'خطا در انجام عملیات');
        }
    }

    public function ResetPassword(Request $request)
    {
        $username = $request->input('username');
        $user = User::where('username', $username)->first();
        if ($username and $user) {
            $user->password = bcrypt(12345678);
            $user->NTCP = 1;
            $user->save();
            $subject = 'Password Resetted';
            $this->logActivity('User => ' . $username . ' ' . $subject, request()->ip(), request()->userAgent(), session('id'));
            return $this->success(true, 'passwordResetted', 'عملیات با موفقیت انجام شد.');
        } else {
            $this->logActivity('Reset Password Failed', request()->ip(), request()->userAgent(), session('id'));
            return $this->alerts(false, 'resetPasswordFailed', 'خطا در انجام عملیات');
        }
    }

    public function newUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users',
        ]);
        if ($validator->fails()) {
            return $this->alerts(false, 'userFounded', 'نام کاربری تکراری وارد شده است.');
        }
        if ($request->input('type') == 3 and !$request->input('province')) {
            return $this->alerts(false, 'emptyProvince', 'استان انتخاب نشده است.');
        }
        $name = $request->input('name');
        $family = $request->input('family');
        $username = $request->input('username');
        $password = $request->input('password');
        $type = $request->input('type');
        switch ($type) {
            case 1:
                $subject = 'ادمین کل';
                break;
            case 2:
                $subject = 'کارشناس فناوری استان';
                break;
        }
        $user = new User();
        $user->name = $name;
        $user->family = $family;
        $user->username = $username;
        $user->password = bcrypt($password);
        $user->type = $type;
        $user->subject = $subject;
        if ($type == 2 and $request->input('province')) {
            $user->province_id = $request->input('province');
        }
        $user->save();
        $this->logActivity('Added User With Name => ' . $username, request()->ip(), request()->userAgent(), session('id'));
        return $this->success(true, 'userAdded', 'کاربر با موفقیت تعریف شد. برای نمایش اطلاعات جدید، لطفا صفحه را رفرش نمایید.');
    }

    public function editUser(Request $request)
    {
        $userID = $request->input('userIdForEdit');
        $name = $request->input('editedName');
        $family = $request->input('editedFamily');
        $type = $request->input('editedType');
        $province = null;
        if ($type == 2 and !$request->input('editedProvince')) {
            return $this->alerts(false, 'emptyProvince', 'استان انتخاب نشده است.');
        }
        if ($type == 2) {
            $province = $request->input('editedProvince');
        }
        switch ($type) {
            case 1:
                $subject = 'ادمین کل';
                break;
            case 2:
                $subject = 'کارشناس فناوری استان';
                break;
        }
        $user = User::find($userID);
        if ($user) {
            $user->name = $name;
            $user->family = $family;
            $user->type = $type;
            $user->subject = $subject;
            $user->province_id = $province;
        }
        $user->save();
        $this->logActivity('Edited User With ID => ' . $userID, request()->ip(), request()->userAgent(), session('id'));
        return $this->success(true, 'userEdited', 'کاربر با موفقیت ویرایش شد. برای نمایش اطلاعات ویرایش شده، صفحه را رفرش نمایید.');
    }

    public function getUserInfo(Request $request)
    {
        $user = User::find($request->userID);
        $this->logActivity('Getting User Information With ID => ' . $request->userID, request()->ip(), request()->userAgent(), session('id'));
        return $user;
    }

    public function index()
    {
        $userList = User::orderBy('id', 'asc')->paginate(10);
        return view('UserManager', ['userList' => $userList]);
    }

}
