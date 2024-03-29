<?php

namespace App\Http\Controllers;

use App\Models\Catalogs\Company;
use App\Models\Person;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:جستجوی کاربر', ['only' => ['search']]);
    }

    public function search(Request $request)
    {
        $work = $request->input('work');
        switch ($work) {
            case 'UserManagerSearch':
                $username = $request->input('username');
                $type = $request->input('type');
                $search = User::query();
                if ($username && $type) {
                    $search->where('username', 'LIKE', '%' . $username . '%')
                        ->where(function ($query) use ($type) {
                            $query->where('type', $type);
                        });
                    $this->logActivity('Search In User Manager With Username => ' . $username . ' And Type => ' . $type, request()->ip(), request()->userAgent(), session('id'));
                } elseif ($username) {
                    $search->where('username', 'LIKE', '%' . $username . '%');
                    $this->logActivity('Search In User Manager With Username => ' . $username, request()->ip(), request()->userAgent(), session('id'));
                } elseif ($type) {
                    $search->where(function ($query) use ($type) {
                        $query->where('type', $type);
                    });
                    $this->logActivity('Search In User Manager With Type => ' . $type, request()->ip(), request()->userAgent(), session('id'));
                }
                $result = $search->get();
                return response()->json($result);
        }
    }
}
