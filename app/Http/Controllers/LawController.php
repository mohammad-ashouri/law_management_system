<?php

namespace App\Http\Controllers;

use App\Models\Catalogs\LawGroup;
use App\Models\Catalogs\Topic;
use App\Models\Catalogs\Type;
use App\Models\Law;
use Illuminate\Http\Request;

class LawController extends Controller
{
    public function create(Request $request)
    {
        return $request->all();
        $name = $request->input('name');

        if (!$name) {
            return $this->alerts(false, 'nullName', 'نام گروه وارد نشده است');
        }

        $table = LawGroup::where('name',$name)->get();
        if ($table->count()>0){
            return $this->alerts(false, 'dupName', 'نام گروه تکراری وارد شده است');
        }

        $table = new LawGroup();
        $table->name=$name;
        $table->save();
        $this->logActivity('Group Added =>' . $table->id, request()->ip(), request()->userAgent(), session('id'));
        return $this->success(true, 'Added', 'برای نمایش اطلاعات جدید، لطفا صفحه را رفرش نمایید.');
    }

    public function update(Request $request)
    {
        $ID = $request->input('group_id');
        $name = $request->input('nameForEdit');

        if (!$ID or !$name) {
            return $this->alerts(false, 'nullName', 'نام گروه وارد نشده است');
        }

        $table = LawGroup::where('name',$name)->get();
        if ($table->count()>0){
            return $this->alerts(false, 'dupName', 'نام گروه تکراری وارد شده است');
        }

        $table = LawGroup::find($ID);
        $table->name=$name;
        $table->save();
        $this->logActivity('Group Edited =>' . $ID, request()->ip(), request()->userAgent(), session('id'));
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
            $this->logActivity('Group' . $ID . 'status changed to =>' . $groupInfo->status, request()->ip(), request()->userAgent(), session('id'));
            $groupInfo->save();
        }
    }

    public function index()
    {
        $lawList = Law::orderBy('created_at', 'desc')->paginate(20);
        $groups=LawGroup::orderBy('name','asc')->get();
        $topics=Topic::orderBy('name','desc')->get();
        return view('LawManager.Index', compact('lawList','groups','topics'));
    }
    public function createIndex()
    {
        $types=Type::where('status',1)->orderBy('name','asc')->get();
        $groups=LawGroup::where('status',1)->orderBy('name','asc')->get();
        $topics=Topic::where('status',1)->orderBy('name','desc')->get();
        return view('LawManager.Create', compact('groups','topics','types'));
    }
}
