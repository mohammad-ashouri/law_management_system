<?php

namespace App\Http\Controllers;

use App\Models\Catalogs\Approver;
use App\Models\Catalogs\LawGroup;
use App\Models\Catalogs\ReferType;
use App\Models\Catalogs\Topic;
use App\Models\Catalogs\Type;
use App\Models\Difference;
use App\Models\Law;
use App\Models\Refer;
use Caxy\HtmlDiff\HtmlDiff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LawController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:لیست قوانین و مصوبات', ['only' => ['index']]);
        $this->middleware('permission:ایجاد مصوبه', ['only' => ['create', 'createIndex']]);
        $this->middleware('permission:ویرایش نوع مصوبه', ['only' => ['update', 'updateIndex']]);
        $this->middleware('permission:جستجوی مصوبه', ['only' => ['search']]);
        $this->middleware('permission:نمایش مصوبه', ['only' => ['show']]);
        $this->middleware('permission:حذف مصوبه', ['only' => ['delete']]);
        $this->middleware('permission:نمایش تاریخچه مصوبه', ['only' => ['showHistory']]);

        ini_set('upload_max_filesize', '32M');
    }

    public array $searchArray = ['أ', 'ة', 'إ', 'ؤ', 'ً', 'ٌ', 'ٍ', 'َ', 'ُ', 'ِ', 'ّ', 'ۀ', '¬', 'ي', 'ك', '‌'];
    public array $replaceArray = ['ا', 'ی', 'ه', 'ا', 'و', '', '', '', '', '', '', '', 'ه', ' ', 'ک', ' '];

    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $filtered = false;
        $isEmpty = false;
        $types = Type::where('status', 1)->orderBy('name', 'asc')->get();
        $groups = LawGroup::where('status', 1)->orderBy('name', 'asc')->get();
        $approvers = Approver::where('status', 1)->orderBy('name', 'desc')->get();
        $topics = Topic::where('status', 1)->orderBy('name', 'desc')->get();
        $lawList = Law::with('type')->with('group')->with('topic')->with('approver')->orderBy('created_at', 'desc')->paginate(20);
        return view('LawManager.Index', compact('lawList', 'groups', 'topics', 'approvers', 'types', 'filtered', 'isEmpty'));
    }

    public function create(Request $request): \Illuminate\Http\JsonResponse
    {
        $lawCode = $request->input('lawCode');
        if (!$lawCode) {
            return $this->alerts(false, 'nullLawCode', 'شماره مصوبه وارد نشده است');
        }

//        $checkLawCode = Law::where('law_code', $lawCode)->get();
//        if ($checkLawCode->count() > 0) {
//            return $this->alerts(false, 'dupLawCode', 'شماره مصوبه تکراری وارد شده است');
//        }

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
        $law->title = str_replace($this->searchArray, $this->replaceArray, $title);

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

        $approver = $request->input('approver');
        if (!$approver) {
            return $this->alerts(false, 'nullGroup', 'تصویب کننده انتخاب نشده است');
        }
        $law->approver_id = $approver;

        $topic = $request->input('topic');
        if (!$topic) {
            return $this->alerts(false, 'nullTopic', 'موضوع انتخاب نشده است');
        }
        $law->topic_id = $topic;

        $body = $request->input('body');
        if (!$body) {
            return $this->alerts(false, 'nullBody', 'کلمات کلیدی وارد نشده است');
        }
        $law->body = str_replace($this->searchArray, $this->replaceArray, $body);

        $keywords = str_replace($this->searchArray, $this->replaceArray, $request->input('keywords'));
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
        }

        if ($issue_year) {
            $law->issue_date = $issue_year . '/' . $issue_month . '/' . $issue_day;
        } else {
            $law->issue_date = null;
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
        }

        if ($promulgation_year) {
            $law->promulgation_date = $promulgation_year . '/' . $promulgation_month . '/' . $promulgation_day;
        } else {
            $law->promulgation_date = null;
        }

        $file = $request->file('file');
        if ($file) {
            $validator = Validator::make($request->all(), [
                'file' => 'required|mimes:jpg,png,jpeg,pdf,doc,docx,zip,rar|max:16384',
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

        if ($request->refer_type and $request->refer_id and (count($request->refer_type) == count($request->refer_id))) {
            if ($request->refer_id and count($request->refer_id) > 0) {
                $refer_type = $request->refer_type;
                $refer_to = $request->refer_id;
                for ($i = 0, $iMax = count($request->refer_id); $i < $iMax; $i++) {
                    $lawRefer = new Refer();
                    $lawRefer->law_from = (int)$law->id;
                    $lawRefer->law_to = (int)$refer_to[$i];
                    $lawRefer->type = (int)$refer_type[$i];
                    $lawRefer->adder = session('id');
                    $lawRefer->save();
                    $this->logActivity('Law Refer Added =>' . $lawRefer->id, request()->ip(), request()->userAgent(), session('id'));
                }
            }
        }
        return response()->json([
            'success' => true,
            'redirect' => route('LawsIndex')
        ]);
    }

    public function update(Request $request): \Illuminate\Http\JsonResponse
    {
        $lawID = $request->input('law_id');
        if (!$lawID) {
            return $this->alerts(false, 'wrongError', 'خطای نامشخص');
        }

        $lawCode = $request->input('lawCode');
        if (!$lawCode) {
            return $this->alerts(false, 'nullLawCode', 'شماره مصوبه وارد نشده است');
        }

//        $checkLawCode = Law::where('law_code', $lawCode)->where('id', '!=', $lawID)->get();
//        if ($checkLawCode->count() > 0) {
//            return $this->alerts(false, 'dupLawCode', 'شماره مصوبه تکراری وارد شده است');
//        }

        $law = Law::find($lawID);
        $newDiff = new Difference();
        $newDiff->law_id = $lawID;
        $newDiff->type = 'body';
        $newDiff->old = $law->body;
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
        $law->title = str_replace($this->searchArray, $this->replaceArray, $title);

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

        $approver = $request->input('approver');
        if (!$approver) {
            return $this->alerts(false, 'nullGroup', 'تصویب کننده انتخاب نشده است');
        }
        $law->approver_id = $approver;

        $topic = $request->input('topic');
        if (!$topic) {
            return $this->alerts(false, 'nullTopic', 'موضوع انتخاب نشده است');
        }
        $law->topic_id = $topic;

        $body = $request->input('body');
        if (!$body) {
            return $this->alerts(false, 'nullBody', 'کلمات کلیدی وارد نشده است');
        }
        $law->body = str_replace($this->searchArray, $this->replaceArray, $body);
        $newDiff->new = $law->body;

        $keywords = str_replace($this->searchArray, $this->replaceArray, $request->input('keywords'));
        if (!$keywords) {
            return $this->alerts(false, 'nullKeyword', 'کلمات کلیدی وارد نشده است');
        }
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
        }

        if ($issue_year) {
            $law->issue_date = $issue_year . '/' . $issue_month . '/' . $issue_day;
        } else {
            $law->issue_date = null;
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
        }

        if ($promulgation_year) {
            $law->promulgation_date = $promulgation_year . '/' . $promulgation_month . '/' . $promulgation_day;
        } else {
            $law->promulgation_date = null;
        }

        $file = $request->file('file');
        if ($file) {
            $validator = Validator::make($request->all(), [
                'file' => 'required|mimes:jpg,png,jpeg,pdf,doc,docx,zip,rar|max:16384',
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

        $law->editor = session('id');
        $law->save();
        if ($newDiff->new != $newDiff->old) {
            $newDiff->editor = session('id');
            $newDiff->save();
        }
        $this->logActivity('Law Edited =>' . $law->id, request()->ip(), request()->userAgent(), session('id'));
        if ($request->refer_type and $request->refer_id and (count($request->refer_type) == count($request->refer_id))) {
            if ($request->refer_id and count($request->refer_id) > 0) {
                $refer_type = $request->refer_type;
                $refer_to = $request->refer_id;
                for ($i = 0, $iMax = count($request->refer_id); $i < $iMax; $i++) {
                    $lawRefer = new Refer();
                    $lawRefer->law_from = (int)$law->id;
                    $lawRefer->law_to = (int)$refer_to[$i];
                    $lawRefer->type = (int)$refer_type[$i];
                    $lawRefer->adder = session('id');
                    $lawRefer->save();
                    $this->logActivity('Law Refer Added =>' . $lawRefer->id, request()->ip(), request()->userAgent(), session('id'));
                }
            }
        }
        return response()->json([
            'success' => true,
            'redirect' => route('LawsIndex')
        ]);
    }

    public function search(Request $request): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $lawCode = $request->input('lawCode');
        $sessionCode = $request->input('sessionCode');
        $title = $request->input('title');
        $type = $request->input('type');
        $group = $request->input('group');
        $approver = $request->input('approver');
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
        if ($approver) {
            $query->where('approver_id', $approver);
        }
        if ($topic) {
            $query->where('topic_id', $topic);
        }
        if (isset($approval_date)) {
            $query->where('approval_date', $approval_date);
        }
        if (isset($issue_date)) {
            $query->where('issue_date', $issue_date);
        }
        if (isset($promulgation_date)) {
            $query->where('promulgation_date', $promulgation_date);
        }
        if ($request->filled('keywords')) {
            $query->where(function ($query) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $query->orWhereJsonContains('keywords', $keyword);
                }
            });
        }
        $query->orderBy('created_at', 'desc');
        $lawList = $query->paginate(20);
        if ($lawList == null or $lawList->isEmpty()) {
            $isEmpty = true;
        } else {
            $isEmpty = false;
        }
//        $lawList = $data->appends(request()->query())->links();


        $types = Type::where('status', 1)->orderBy('name', 'asc')->get();
        $groups = LawGroup::where('status', 1)->orderBy('name', 'asc')->get();
        $approvers = Approver::where('status', 1)->orderBy('name', 'desc')->get();
        $topics = Topic::where('status', 1)->orderBy('name', 'desc')->get();
        $allRequests = $request->all();
        return view('LawManager.Index', compact('lawList', 'groups', 'topics', 'types', 'approvers', 'isEmpty', 'allRequests'));
    }

    public function createIndex(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $types = Type::where('status', 1)->orderBy('name', 'asc')->get();
        $groups = LawGroup::where('status', 1)->orderBy('name', 'asc')->get();
        $approvers = Approver::where('status', 1)->orderBy('name', 'desc')->get();
        $topics = Topic::where('status', 1)->orderBy('name', 'desc')->get();
        $referTypes = ReferType::where('status', 1)->orderBy('name', 'desc')->get();
        return view('LawManager.Create', compact('groups', 'topics', 'approvers', 'types', 'referTypes'));
    }

    public function updateIndex($id): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $lawInfo = Law::with('type')->with('group')->with('topic')->with('approver')->with('approver')->find($id);
        $types = Type::where('status', 1)->orderBy('name', 'asc')->get();
        $groups = LawGroup::where('status', 1)->orderBy('name', 'asc')->get();
        $approvers = Approver::where('status', 1)->orderBy('name', 'desc')->get();
        $topics = Topic::where('status', 1)->orderBy('name', 'desc')->get();
        $referTypes = ReferType::where('status', 1)->orderBy('name', 'desc')->get();
        $refers = Refer::with('typeInfo')->with('lawFromInfo')->with('lawToInfo')->where('law_from', $id)->orderBy('id')->get();
        return view('LawManager.Update', compact('lawInfo', 'groups', 'approvers', 'topics', 'types', 'referTypes', 'refers'));
    }

    public function show($id): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $lawInfo = Law::with('type')->with('group')->with('topic')->with('approver')->with('approver')->find($id);
        $types = Type::where('status', 1)->orderBy('name', 'asc')->get();
        $groups = LawGroup::where('status', 1)->orderBy('name', 'asc')->get();
        $approvers = Approver::where('status', 1)->orderBy('name', 'desc')->get();
        $topics = Topic::where('status', 1)->orderBy('name', 'desc')->get();
        $referTypes = ReferType::where('status', 1)->orderBy('name', 'desc')->get();
        $refers = Refer::with('typeInfo')->with('lawFromInfo')->with('lawToInfo')->where('law_from', $id)->orderBy('id')->get();
        return view('LawManager.Show', compact('lawInfo', 'groups', 'approvers', 'topics', 'types', 'referTypes', 'refers'));
    }

    public function delete(Request $request): \Illuminate\Http\JsonResponse
    {
        $id = $request->input('id');
        $law = Law::find($id);
        if ($law->delete()) {
            $this->logActivity('Law Deleted =>' . $law->id, request()->ip(), request()->userAgent(), session('id'));
            Refer::where('law_to', $id)->delete();
            return $this->success(true, 'Deleted', 'برای نمایش اطلاعات جدید، لطفا صفحه را رفرش نمایید.');
        }
        return $this->alerts(false, 'wrongError', 'خطای نامشخص.');
    }

    public function showHistory($id): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $lawDiffs = Difference::with('lawInfo')->with('editorInfo')->where('law_id', $id)->orderByDesc('id')->get();
        $lawName = Law::find($id)->pluck('title');
        return view('LawManager.Difference_history', compact('lawDiffs', 'lawName'));
    }

    public function getLawInfo(Request $request): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Builder|string|array
    {
        $lawInfo = Law::with('group')->with('type')->with('approver')->with('topic')->find($request->law_id);
        if ($lawInfo) {
            return $lawInfo;
        }
        return 'not found';
    }

    public function removeRefer(Request $request): \Illuminate\Http\JsonResponse
    {
        $refer = Refer::find($request->refer_id);
        if ($refer) {
            $refer->delete();
            $this->logActivity('Refer Deleted =>' . $request->refer_id, request()->ip(), request()->userAgent(), session('id'));
            return $this->success(true, 'Deleted', 'برای نمایش اطلاعات جدید، لطفا صفحه را رفرش نمایید.');
        }
        return $this->alerts(false, 'wrongID', 'خطای نامشخص');

    }
}
