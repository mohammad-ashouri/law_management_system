<?php

namespace App\Http\Controllers\Catalogs;

use App\Http\Controllers\Controller;
use App\Models\Catalogs\LawGroup;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function create(Request $request)
    {
        $name = $request->input('name');

        if (!$name) {
            return $this->alerts(false, 'nullName', 'نام گروه وارد نشده است');
        }

        $table = LawGroup::where('name',$name)->first();
        if ($name==$table->name){
            return $this->alerts(false, 'dupName', 'نام گروه تکراری وارد شده است');
        }

        $table = new LawGroup();
        $table->name=$name;
        $table->save();
        $this->logActivity('Added =>' . $table->id, request()->ip(), request()->userAgent(), session('id'));
        return $this->success(true, 'Added', 'برای نمایش اطلاعات جدید، لطفا صفحه را رفرش نمایید.');
    }

    public function update(Request $request)
    {
        $ID = $request->input('group_id');
        $name = $request->input('nameForEdit');

        if (!$ID or !$name) {
            return $this->alerts(false, 'nullName', 'نام گروه وارد نشده است');
        }

        $table = LawGroup::find($ID);
        if ($name===$table->name){
            return $this->alerts(false, 'dupName', 'نام گروه تکراری وارد شده است');
        }

        $table->name=$name;
        $table->save();
        $this->logActivity('Edited =>' . $ID, request()->ip(), request()->userAgent(), session('id'));
        return $this->success(true, 'Edited', 'برای نمایش اطلاعات جدید، لطفا صفحه را رفرش نمایید.');
    }

    public function getInfo(Request $request)
    {
        $ID = $request->input('id');
        if ($ID) {
            return LawGroup::find($ID);
        }
    }

    public function changeStatus(Request $request)
    {
        $ID = $request->input('id');
        if ($ID) {
            $groupInfo = LawGroup::find($ID);
            if ($groupInfo->status === 1) {
                $groupInfo->status = 0;
            } else {
                $groupInfo->status = 1;
            }
            $this->logActivity('Group status changed to =>' . $groupInfo->status, request()->ip(), request()->userAgent(), session('id'));
            $groupInfo->save();
        }
    }

    public function index()
    {
        $groupList = LawGroup::orderBy('name', 'asc')->paginate(20);
        return \view('Catalogs.GroupCatalog', ['groupList' => $groupList]);
    }
}
