<?php

namespace App\Http\Controllers;

use App\Models\Catalogs\LawGroup;
use App\Models\Catalogs\Topic;
use App\Models\Catalogs\Type;
use App\Models\Law;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LawController extends Controller
{
    public function create(Request $request)
    {

        $lawCode = $request->input('lawCode');
        if (!$lawCode) {
            return $this->alerts(false, 'nullLawCode', 'شماره مصوبه وارد نشده است');
        }

        $checkLawCode = Law::where('law_code', $lawCode)->get();
        if ($checkLawCode->count() > 0) {
            return $this->alerts(false, 'dupLawCode', 'شماره مصوبه تکراری وارد شده است');
        }

        $law = new Law();
        $law->law_code = $lawCode;

        $sessionCode = $request->input('sessionCode');
//        if (!$sessionCode) {
//            return $this->alerts(false, 'nullSessionCode', 'شماره جلسه وارد نشده است');
//        }
        $law->session_code = $sessionCode;

        $title = $request->input('title');
        if (!$title) {
            return $this->alerts(false, 'nullTitle', 'عنوان وارد نشده است');
        }
        $law->title = $title;

        $type = $request->input('type');
        if (!$type) {
            return $this->alerts(false, 'nullType', 'نوع مصوبه انتخاب نشده است');
        }
        $law->type_id = $type;

        $group = $request->input('group');
        if (!$group) {
            return $this->alerts(false, 'nullGroup', 'گروه انتخاب نشده است');
        }
        $law->group_id = $group;

        $topic = $request->input('topic');
        if (!$topic) {
            return $this->alerts(false, 'nullTopic', 'موضوع انتخاب نشده است');
        }
        $law->topic_id = $topic;

        $body = $request->input('body');
        if (!$body) {
            return $this->alerts(false, 'nullBody', 'کلمات کلیدی وارد نشده است');
        }
        $law->body = $body;

        $keywords = $request->input('keywords');
        if (!$keywords) {
            return $this->alerts(false, 'nullKeyword', 'کلمات کلیدی وارد نشده است');
        }
//        dd($keywords);
        $keywords = explode('||', $keywords);
        $keywords = json_encode($keywords, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        $law->keywords = $keywords;

        $approval_day = $request->input('approval_day');
        $approval_month = $request->input('approval_month');
        $approval_year = $request->input('approval_year');
        if (!$approval_year or !$approval_month or !$approval_day) {
            return $this->alerts(false, 'nullApprovalDate', 'تاریخ تصویب به صورت کامل انتخاب نشده است');
        }
        $law->approval_date = $approval_year . '/' . $approval_month . '/' . $approval_day;

        $issue_day = $request->input('issue_day');
        $issue_month = $request->input('issue_month');
        $issue_year = $request->input('issue_year');
        if (
            ($issue_day && !$issue_month && !$issue_year) ||
            (!$issue_day && $issue_month && $issue_year) ||
            (!$issue_day && $issue_month && !$issue_year) ||
            ($issue_day && !$issue_month && $issue_year)
        ) {
            return $this->alerts(false, 'nullIssueDate', 'تاریخ صدور به صورت کامل انتخاب نشده است');
        } else {
            $law->issue_date = $issue_year . '/' . $issue_month . '/' . $issue_day;
        }

        $promulgation_day = $request->input('promulgation_day');
        $promulgation_month = $request->input('promulgation_month');
        $promulgation_year = $request->input('promulgation_year');
        if (
            ($promulgation_day && !$promulgation_month && !$promulgation_year) ||
            (!$promulgation_day && $promulgation_month && $promulgation_year) ||
            (!$promulgation_day && $promulgation_month && !$promulgation_year) ||
            ($promulgation_day && !$promulgation_month && $promulgation_year)
        ) {
            return $this->alerts(false, 'nullPromulgationDate', 'تاریخ ابلاغ به صورت کامل انتخاب نشده است');
        } else {
            $law->promulgation_date = $promulgation_year . '/' . $promulgation_month . '/' . $promulgation_day;
        }

        $file = $request->file('file');
        if ($file) {
            $validator = Validator::make($request->all(), [
                'file' => 'required|mimes:jpg,png,jpeg,pdf,doc,docx|max:2048',
            ]);
            if ($validator->fails()) {
                return $this->alerts(false, 'wrongFile', 'فایل نامعتبر انتخاب شده است.');
            }
            $folderName = str_replace(array('/', '\\'), '', bcrypt($file->getClientOriginalName()));
            $postFilePath = $file->storeAs('public/LawFiles/' . $folderName, $file->getClientOriginalName());
            if ($postFilePath) {
                $law->file = $postFilePath;
            }
        }

        $law->adder = session('id');
        $law->save();
        $this->logActivity('Law Added =>' . $law->id, request()->ip(), request()->userAgent(), session('id'));
        return $this->success(true, 'Added', 'برای نمایش اطلاعات جدید، لطفا صفحه را رفرش نمایید.');
    }

    public function update(Request $request)
    {
        $lawID = $request->input('law_id');
        if (!$lawID) {
            return $this->alerts(false, 'wrongError', 'خطای نامشخص');
        }

        $lawCode = $request->input('lawCode');
        if (!$lawCode) {
            return $this->alerts(false, 'nullLawCode', 'شماره مصوبه وارد نشده است');
        }

        $checkLawCode = Law::where('law_code', $lawCode)->where('id', '!=', $lawID)->get();
        if ($checkLawCode->count() > 0) {
            return $this->alerts(false, 'dupLawCode', 'شماره مصوبه تکراری وارد شده است');
        }

        $law = Law::find($lawID);
        $law->law_code = $lawCode;

        $sessionCode = $request->input('sessionCode');
//        if (!$sessionCode) {
//            return $this->alerts(false, 'nullSessionCode', 'شماره جلسه وارد نشده است');
//        }
        $law->session_code = $sessionCode;

        $title = $request->input('title');
        if (!$title) {
            return $this->alerts(false, 'nullTitle', 'عنوان وارد نشده است');
        }
        $law->title = $title;

        $type = $request->input('type');
        if (!$type) {
            return $this->alerts(false, 'nullType', 'نوع مصوبه انتخاب نشده است');
        }
        $law->type_id = $type;

        $group = $request->input('group');
        if (!$group) {
            return $this->alerts(false, 'nullGroup', 'گروه انتخاب نشده است');
        }
        $law->group_id = $group;

        $topic = $request->input('topic');
        if (!$topic) {
            return $this->alerts(false, 'nullTopic', 'موضوع انتخاب نشده است');
        }
        $law->topic_id = $topic;

        $body = $request->input('body');
        if (!$body) {
            return $this->alerts(false, 'nullBody', 'کلمات کلیدی وارد نشده است');
        }
        $law->body = $body;

        $keywords = $request->input('keywords');
        if (!$keywords) {
            return $this->alerts(false, 'nullKeyword', 'کلمات کلیدی وارد نشده است');
        }
//        dd($keywords);
        $keywords = explode('||', $keywords);
        $keywords = json_encode($keywords, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        $law->keywords = $keywords;

        $approval_day = $request->input('approval_day');
        $approval_month = $request->input('approval_month');
        $approval_year = $request->input('approval_year');
        if (!$approval_year or !$approval_month or !$approval_day) {
            return $this->alerts(false, 'nullApprovalDate', 'تاریخ تصویب به صورت کامل انتخاب نشده است');
        }
        $law->approval_date = $approval_year . '/' . $approval_month . '/' . $approval_day;

        $issue_day = $request->input('issue_day');
        $issue_month = $request->input('issue_month');
        $issue_year = $request->input('issue_year');
        if (
            ($issue_day && !$issue_month && !$issue_year) ||
            (!$issue_day && $issue_month && $issue_year) ||
            (!$issue_day && $issue_month && !$issue_year) ||
            ($issue_day && !$issue_month && $issue_year)
        ) {
            return $this->alerts(false, 'nullIssueDate', 'تاریخ صدور به صورت کامل انتخاب نشده است');
        } else {
            $law->issue_date = $issue_year . '/' . $issue_month . '/' . $issue_day;
        }

        $promulgation_day = $request->input('promulgation_day');
        $promulgation_month = $request->input('promulgation_month');
        $promulgation_year = $request->input('promulgation_year');
        if (
            ($promulgation_day && !$promulgation_month && !$promulgation_year) ||
            (!$promulgation_day && $promulgation_month && $promulgation_year) ||
            (!$promulgation_day && $promulgation_month && !$promulgation_year) ||
            ($promulgation_day && !$promulgation_month && $promulgation_year)
        ) {
            return $this->alerts(false, 'nullPromulgationDate', 'تاریخ ابلاغ به صورت کامل انتخاب نشده است');
        } else {
            $law->promulgation_date = $promulgation_year . '/' . $promulgation_month . '/' . $promulgation_day;
        }

        $file = $request->file('file');
        if ($file) {
            $validator = Validator::make($request->all(), [
                'file' => 'required|mimes:jpg,png,jpeg,pdf,doc,docx|max:2048',
            ]);
            if ($validator->fails()) {
                return $this->alerts(false, 'wrongFile', 'فایل نامعتبر انتخاب شده است.');
            }
            $folderName = str_replace(array('/', '\\'), '', bcrypt($file->getClientOriginalName()));
            $postFilePath = $file->storeAs('public/LawFiles/' . $folderName, $file->getClientOriginalName());
            if ($postFilePath) {
                $law->file = $postFilePath;
            }
        }

        $law->adder = session('id');
        $law->save();
        $this->logActivity('Law Edited =>' . $law->id, request()->ip(), request()->userAgent(), session('id'));
        return $this->success(true, 'Edited', 'برای نمایش اطلاعات جدید، لطفا صفحه را رفرش نمایید.');
    }

    public function index()
    {
        $filtered = false;
        $types = Type::where('status', 1)->orderBy('name', 'asc')->get();
        $groups = LawGroup::where('status', 1)->orderBy('name', 'asc')->get();
        $topics = Topic::where('status', 1)->orderBy('name', 'desc')->get();
        $lawList = Law::with('type')->with('group')->with('topic')->orderBy('created_at', 'desc')->paginate(20);
        return view('LawManager.Index', compact('lawList', 'groups', 'topics', 'types', 'filtered'));
    }

    public function search(Request $request)
    {
        $lawCode = $request->input('lawCode');
        $sessionCode = $request->input('sessionCode');
        $title = $request->input('title');
        $type = $request->input('type');
        $group = $request->input('group');
        $topic = $request->input('topic');
        $approval_day = $request->input('approval_day');
        $approval_month = $request->input('approval_month');
        $approval_year = $request->input('approval_year');
        if ($approval_day and $approval_month and $approval_year) {
            $approval_date = $approval_year . '/' . $approval_month . '/' . $approval_day;
        }
        $issue_day = $request->input('issue_day');
        $issue_month = $request->input('issue_month');
        $issue_year = $request->input('issue_year');
        if ($issue_day and $issue_month and $issue_year) {
            $issue_date = $issue_year . '/' . $issue_month . '/' . $issue_day;
        }
        $promulgation_day = $request->input('promulgation_day');
        $promulgation_month = $request->input('promulgation_month');
        $promulgation_year = $request->input('promulgation_year');
        if ($promulgation_day and $promulgation_month and $promulgation_year) {
            $promulgation_date = $promulgation_year . '/' . $promulgation_month . '/' . $promulgation_day;
        }
        $keywords = explode('||', $request->input('keywords'));

        $query = Law::query();
        $query->with('type')->with('group')->with('topic');
        if ($lawCode) {
            $query->where('law_code', $lawCode);
        }
        if ($sessionCode) {
            $query->where('session_code', $sessionCode);
        }
        if ($title) {
            $query->where('title', 'LIKE', '%' . $title . '%');
        }
        if ($type) {
            $query->where('type_id', $type);
        }
        if ($group) {
            $query->where('group_id', $group);
        }
        if ($topic) {
            $query->where('topic_id', $topic);
        }
        if (@$approval_date) {
            $query->where('approval_date', $approval_date);
        }
        if (@$issue_date) {
            $query->where('issue_date', $issue_date);
        }
        if (@$promulgation_date) {
            $query->where('promulgation_date', $promulgation_date);
        }
        if ($request->filled('keywords')) {
            $query->where(function ($query) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $query->orWhereJsonContains('keywords', $keyword);
                }
            });
        }
        $query->orderBy('created_at', 'desc')->paginate(20);

        $filtered = true;
        $lawList = $query->get();
        $types = Type::where('status', 1)->orderBy('name', 'asc')->get();
        $groups = LawGroup::where('status', 1)->orderBy('name', 'asc')->get();
        $topics = Topic::where('status', 1)->orderBy('name', 'desc')->get();
        return view('LawManager.Index', compact('lawList', 'groups', 'topics', 'types', 'filtered'));
    }

    public function createIndex()
    {
        $types = Type::where('status', 1)->orderBy('name', 'asc')->get();
        $groups = LawGroup::where('status', 1)->orderBy('name', 'asc')->get();
        $topics = Topic::where('status', 1)->orderBy('name', 'desc')->get();
        return view('LawManager.Create', compact('groups', 'topics', 'types'));
    }

    public function updateIndex($id)
    {
        $lawInfo = Law::with('type')->with('group')->with('topic')->find($id);
        $types = Type::where('status', 1)->orderBy('name', 'asc')->get();
        $groups = LawGroup::where('status', 1)->orderBy('name', 'asc')->get();
        $topics = Topic::where('status', 1)->orderBy('name', 'desc')->get();
        return view('LawManager.Update', compact('lawInfo', 'groups', 'topics', 'types'));
    }

    public function delete(Request $request)
    {
        $id = $request->input('id');
        $law = Law::find($id);
        if ($law->delete()) {
            $this->logActivity('Law Deleted =>' . $law->id, request()->ip(), request()->userAgent(), session('id'));
            return $this->success(true, 'Deleted', 'برای نمایش اطلاعات جدید، لطفا صفحه را رفرش نمایید.');
        }
        return $this->alerts(false, 'wrongError', 'خطای نامشخص.');
    }
}
