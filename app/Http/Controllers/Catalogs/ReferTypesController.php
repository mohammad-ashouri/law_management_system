<?php

namespace App\Http\Controllers\Catalogs;

use App\Http\Controllers\Controller;
use App\Models\Catalogs\ReferType;
use Illuminate\Http\Request;

class ReferTypesController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:لیست انواع ارتباط', ['only' => ['index']]);
        $this->middleware('permission:ایجاد ارتباط', ['only' => ['create']]);
        $this->middleware('permission:ویرایش ارتباط', ['only' => ['update']]);
        $this->middleware('permission:تغییر وضعیت ارتباط', ['only' => ['changeStatus']]);
    }

    public function index()
    {
        $referTypeList = ReferType::orderBy('name', 'asc')->paginate(20);
        return \view('Catalogs.ReferTypeCatalog', ['referTypeList' => $referTypeList]);
    }

    public function create(Request $request)
    {
        $name = $request->input('name');

        if (!$name) {
            return $this->alerts(false, 'nullName', 'نام ارتباط وارد نشده است');
        }

        $table = ReferType::where('name', $name)->get();
        if ($table->count() > 0) {
            return $this->alerts(false, 'dupName', 'نام ارتباط تکراری وارد شده است');
        }

        $table = new ReferType();
        $table->name = $name;
        $table->save();
        $this->logActivity('ReferType Added =>' . $table->id, request()->ip(), request()->userAgent(), session('id'));
        return $this->success(true, 'Added', 'برای نمایش اطلاعات جدید، لطفا صفحه را رفرش نمایید.');
    }

    public function update(Request $request)
    {
        $ID = $request->input('referTypes_id');
        $name = $request->input('nameForEdit');

        if (!$ID or !$name) {
            return $this->alerts(false, 'nullName', 'نام ارتباط وارد نشده است');
        }

        $table = ReferType::where('name', $name)->get();
        if ($table->count() > 0) {
            return $this->alerts(false, 'dupName', 'نام ارتباط تکراری وارد شده است');
        }

        $table = ReferType::find($ID);
        $table->name = $name;
        $table->save();
        $this->logActivity('ReferType Edited =>' . $ID, request()->ip(), request()->userAgent(), session('id'));
        return $this->success(true, 'Edited', 'برای نمایش اطلاعات جدید، لطفا صفحه را رفرش نمایید.');
    }

    public function getInfo(Request $request)
    {
        $ID = $request->input('id');
        if ($ID) {
            return ReferType::find($ID);
        }
    }

    public function changeStatus(Request $request)
    {
        $ID = $request->input('id');
        if ($ID) {
            $referTypeInfo = ReferType::find($ID);
            if ($referTypeInfo->status === 1) {
                $referTypeInfo->status = 0;
            } else {
                $referTypeInfo->status = 1;
            }
            $this->logActivity('ReferType' . $ID . 'status changed to =>' . $referTypeInfo->status, request()->ip(), request()->userAgent(), session('id'));
            $referTypeInfo->save();
        }
    }

}
