<?php

namespace App\Http\Controllers\Catalogs;

use App\Http\Controllers\Controller;
use App\Models\Catalogs\Type;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:لیست انواع مصوبه', ['only' => ['index']]);
        $this->middleware('permission:ایجاد نوع مصوبه', ['only' => ['create']]);
        $this->middleware('permission:ویرایش نوع مصوبه', ['only' => ['update']]);
        $this->middleware('permission:تغییر وضعیت نوع مصوبه', ['only' => ['changeStatus']]);
    }

    public function index()
    {
        $typeList = Type::orderBy('name', 'asc')->paginate(20);
        return \view('Catalogs.TypeCatalog', ['typeList' => $typeList]);
    }

    public function create(Request $request)
    {
        $name = $request->input('name');

        if (!$name) {
            return $this->alerts(false, 'nullName', 'موضوع وارد نشده است');
        }

        $table = Type::where('name', $name)->get();
        if ($table->count() > 0) {
            return $this->alerts(false, 'dupName', 'موضوع تکراری وارد شده است');
        }

        $table = new Type();
        $table->name = $name;
        $table->save();
        $this->logActivity('Type Added =>' . $table->id, request()->ip(), request()->userAgent(), session('id'));
        return $this->success(true, 'Added', 'برای نمایش اطلاعات جدید، لطفا صفحه را رفرش نمایید.');
    }

    public function update(Request $request)
    {
        $ID = $request->input('type_id');
        $name = $request->input('nameForEdit');

        if (!$ID or !$name) {
            return $this->alerts(false, 'nullName', 'موضوع وارد نشده است');
        }

        $table = Type::where('name', $name)->get();
        if ($table->count() > 0) {
            return $this->alerts(false, 'dupName', 'موضوع تکراری وارد شده است');
        }

        $table = Type::find($ID);
        $table->name = $name;
        $table->save();
        $this->logActivity('Type Edited =>' . $ID, request()->ip(), request()->userAgent(), session('id'));
        return $this->success(true, 'Edited', 'برای نمایش اطلاعات جدید، لطفا صفحه را رفرش نمایید.');
    }

    public function getInfo(Request $request)
    {
        $ID = $request->input('id');
        if ($ID) {
            return Type::find($ID);
        }
    }

    public function changeStatus(Request $request)
    {
        $ID = $request->input('id');
        if ($ID) {
            $topicInfo = Type::find($ID);
            if ($topicInfo->status === 1) {
                $topicInfo->status = 0;
            } else {
                $topicInfo->status = 1;
            }
            $this->logActivity('Topic' . $ID . 'status changed to =>' . $topicInfo->status, request()->ip(), request()->userAgent(), session('id'));
            $topicInfo->save();
        }
    }

}
