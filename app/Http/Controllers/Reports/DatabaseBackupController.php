<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\DatabaseBackup;
use Illuminate\Support\Facades\Artisan;

class DatabaseBackupController extends Controller
{
    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $backups=DatabaseBackup::with('creatorInfo')->orderBy('id','desc')->paginate(15);
        return view('Reports.DatabaseBackup',compact('backups'));
    }

    public function createBackup(): \Illuminate\Http\JsonResponse
    {
        $command=Artisan::call('database:backup');
        if ($command===0) {
            $this->logActivity('Backup created!', request()->ip(), request()->userAgent(), session('id'));
            return $this->success(200, 'success', 'بکاپ با موفقیت ایجاد شد!');
        } else {
            return $this->alerts(false, 'error', 'اجرای دستور با خطا مواجه شد.');
        }
    }
}
