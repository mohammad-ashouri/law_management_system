<?php

namespace App\Http\Controllers\Catalogs;

use App\Http\Controllers\Controller;
use App\Models\Catalogs\Approver;
use Illuminate\Http\Request;

class ApproverController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:لیست تصویب کنندگان', ['only' => ['index']]);
        $this->middleware('permission:ایجاد تصویب کننده', ['only' => ['create']]);
        $this->middleware('permission:ویرایش تصویب کننده', ['only' => ['update']]);
        $this->middleware('permission:تغییر وضعیت تصویب کننده', ['only' => ['changeStatus']]);
    }

    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $approverList = Approver::orderBy('name', 'asc')->paginate(20);
        return \view('Catalogs.ApproverCatalog', ['approverList' => $approverList]);
    }

    public function create(Request $request): \Illuminate\Http\JsonResponse
    {
        $name = $request->input('name');

        if (!$name) {
            return $this->alerts(false, 'nullName', 'نام وارد نشده است');
        }

        $table = Approver::where('name', $name)->get();
        if ($table->count() > 0) {
            return $this->alerts(false, 'dupName', 'نام تکراری وارد شده است');
        }

        $table = new Approver();
        $table->name = $name;
        $table->save();
        $this->logActivity('Approver Added =>' . $table->id, request()->ip(), request()->userAgent(), session('id'));
        return $this->success(true, 'Added', 'برای نمایش اطلاعات جدید، لطفا صفحه را رفرش نمایید.');
    }

    public function update(Request $request): \Illuminate\Http\JsonResponse
    {
        $ID = $request->input('approver_id');
        $name = $request->input('nameForEdit');

        if (!$ID or !$name) {
            return $this->alerts(false, 'nullName', 'موضوع وارد نشده است');
        }

        $table = Approver::where('name', $name)->get();
        if ($table->count() > 0) {
            return $this->alerts(false, 'dupName', 'موضوع تکراری وارد شده است');
        }

        $table = Approver::find($ID);
        $table->name = $name;
        $table->save();
        $this->logActivity('Approver Edited =>' . $ID, request()->ip(), request()->userAgent(), session('id'));
        return $this->success(true, 'Edited', 'برای نمایش اطلاعات جدید، لطفا صفحه را رفرش نمایید.');
    }

    public function getInfo(Request $request)
    {
        $ID = $request->input('id');
        if ($ID) {
            return Approver::find($ID);
        }
    }

    public function changeStatus(Request $request): void
    {
        $ID = $request->input('id');
        if ($ID) {
            $approverInfo = Approver::find($ID);
            if ($approverInfo->status === 1) {
                $approverInfo->status = 0;
            } else {
                $approverInfo->status = 1;
            }
            $this->logActivity('Approver' . $ID . 'status changed to =>' . $approverInfo->status, request()->ip(), request()->userAgent(), session('id'));
            $approverInfo->save();
        }
    }

}
