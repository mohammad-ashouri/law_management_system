<?php

namespace App\Http\Controllers\Catalogs;

use App\Http\Controllers\Controller;
use App\Models\Catalogs\Topic;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    public function create(Request $request)
    {
        $name = $request->input('name');

        if (!$name) {
            return $this->alerts(false, 'nullName', 'موضوع وارد نشده است');
        }

        $table = Topic::where('name',$name)->get();
        if ($table->count()>0){
            return $this->alerts(false, 'dupName', 'موضوع تکراری وارد شده است');
        }

        $table = new Topic();
        $table->name=$name;
        $table->save();
        $this->logActivity('Topic Added =>' . $table->id, request()->ip(), request()->userAgent(), session('id'));
        return $this->success(true, 'Added', 'برای نمایش اطلاعات جدید، لطفا صفحه را رفرش نمایید.');
    }

    public function update(Request $request)
    {
        $ID = $request->input('topic_id');
        $name = $request->input('nameForEdit');

        if (!$ID or !$name) {
            return $this->alerts(false, 'nullName', 'موضوع وارد نشده است');
        }

        $table = Topic::where('name',$name)->get();
        if ($table->count()>0){
            return $this->alerts(false, 'dupName', 'موضوع تکراری وارد شده است');
        }

        $table = Topic::find($ID);
        $table->name=$name;
        $table->save();
        $this->logActivity('Topic Edited =>' . $ID, request()->ip(), request()->userAgent(), session('id'));
        return $this->success(true, 'Edited', 'برای نمایش اطلاعات جدید، لطفا صفحه را رفرش نمایید.');
    }

    public function getInfo(Request $request)
    {
        $ID = $request->input('id');
        if ($ID) {
            return Topic::find($ID);
        }
    }

    public function changeStatus(Request $request)
    {
        $ID = $request->input('id');
        if ($ID) {
            $topicInfo = Topic::find($ID);
            if ($topicInfo->status === 1) {
                $topicInfo->status = 0;
            } else {
                $topicInfo->status = 1;
            }
            $this->logActivity('Topic' . $ID . 'status changed to =>' . $topicInfo->status, request()->ip(), request()->userAgent(), session('id'));
            $topicInfo->save();
        }
    }

    public function index()
    {
        $topicList = Topic::orderBy('name', 'asc')->paginate(20);
        return \view('Catalogs.TopicCatalog', ['topicList' => $topicList]);
    }
}
