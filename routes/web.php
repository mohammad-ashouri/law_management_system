<?php

use App\Http\Controllers\Catalogs\ApproverController;
use App\Http\Controllers\Catalogs\GroupController;
use App\Http\Controllers\Catalogs\ReferTypesController;
use App\Http\Controllers\Catalogs\RoleController;
use App\Http\Controllers\Catalogs\TopicController;
use App\Http\Controllers\Catalogs\TypeController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LawController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Reports\DatabaseBackupController;
use App\Http\Controllers\Reports\ExcelAllReportsController;
use App\Http\Controllers\Reports\PDFReportController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserManager;
use App\Http\Middleware\CheckLoginMiddleware;
use App\Http\Middleware\MenuMiddleware;
use App\Http\Middleware\NTCPMiddleware;
use App\Http\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


//Login Routes
Route::get('/', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }
    return redirect()->route('dashboard');
});
Route::get('/home', function () {
    Auth::logout();
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::middleware(ThrottleRequests::class)->post('/login', [LoginController::class, 'login']);
Route::get('/captcha', [LoginController::class, 'getCaptcha'])->name('captcha');


//Panel Routes
Route::middleware(CheckLoginMiddleware::class)->middleware(MenuMiddleware::class)->group(function () {
    Route::get('/dateandtime', [DashboardController::class, 'jalaliDateAndTime']);
    Route::get('/date', [DashboardController::class, 'jalaliDate']);
    Route::get('/Profile', [DashboardController::class, 'Profile'])->name('Profile');
    Route::post('/ChangePasswordInc', [DashboardController::class, 'ChangePasswordInc']);
    Route::post('/ChangeUserImage', [DashboardController::class, 'ChangeUserImage']);
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/Search', [SearchController::class, 'search'])->name('Search');

    Route::middleware(NTCPMiddleware::class)->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        //Search Route
        Route::middleware('roleAuthorization:1')->group(callback: function () {
            //User Manager
            Route::get('/UserManager', [UserManager::class, 'index'])->name('UserManager');
            Route::get('/GetUserInfo', [UserManager::class, 'getUserInfo'])->name('GetUserInfo');
            Route::Post('/NewUser', [UserManager::class, 'newUser'])->name('NewUser');
            Route::Post('/EditUser', [UserManager::class, 'editUser'])->name('EditUser');
            Route::Post('/ChangeUserActivationStatus', [UserManager::class, 'changeUserActivationStatus'])->name('ChangeUserActivationStatus');
            Route::Post('/ChangeUserNTCP', [UserManager::class, 'ChangeUserNTCP'])->name('ChangeUserNTCP');
            Route::Post('/ResetPassword', [UserManager::class, 'ResetPassword'])->name('ResetPassword');

            //Types Catalogs
            Route::group(['prefix' => 'Types'], function () {
                Route::get('/', [TypeController::class, 'index']);
                Route::post('/create', [TypeController::class, 'create']);
                Route::get('/getInfo', [TypeController::class, 'getInfo']);
                Route::post('/update', [TypeController::class, 'update']);
                Route::post('/changeStatus', [TypeController::class, 'changeStatus']);
            });
            //Group Catalogs
            Route::group(['prefix' => 'Groups'], function () {
                Route::get('/', [GroupController::class, 'index']);
                Route::post('/create', [GroupController::class, 'create']);
                Route::get('/getInfo', [GroupController::class, 'getInfo']);
                Route::post('/update', [GroupController::class, 'update']);
                Route::post('/changeStatus', [GroupController::class, 'changeStatus']);
            });
            //Topic Catalogs
            Route::group(['prefix' => 'Topics'], function () {
                Route::get('/', [TopicController::class, 'index']);
                Route::post('/create', [TopicController::class, 'create']);
                Route::get('/getInfo', [TopicController::class, 'getInfo']);
                Route::post('/update', [TopicController::class, 'update']);
                Route::post('/changeStatus', [TopicController::class, 'changeStatus']);
            });
            //Approver Catalogs
            Route::group(['prefix' => 'Approvers'], function () {
                Route::get('/', [ApproverController::class, 'index']);
                Route::post('/create', [ApproverController::class, 'create']);
                Route::get('/getInfo', [ApproverController::class, 'getInfo']);
                Route::post('/update', [ApproverController::class, 'update']);
                Route::post('/changeStatus', [ApproverController::class, 'changeStatus']);
            });
            //Refer Types Catalogs
            Route::group(['prefix' => 'ReferTypes'], function () {
                Route::get('/', [ReferTypesController::class, 'index']);
                Route::post('/create', [ReferTypesController::class, 'create']);
                Route::get('/getInfo', [ReferTypesController::class, 'getInfo']);
                Route::post('/update', [ReferTypesController::class, 'update']);
                Route::post('/changeStatus', [ReferTypesController::class, 'changeStatus']);
            });

            //Role Controller
            Route::resource('/Roles',RoleController::class);

            //Law Management
            Route::group(['prefix' => 'Laws'], function () {
                Route::get('/', [LawController::class, 'index'])->name('LawsIndex');
                Route::get('/new', [LawController::class, 'createIndex']);
                Route::post('/create', [LawController::class, 'create']);
                Route::get('/search', [LawController::class, 'search']);
                Route::get('/edit/{id}', [LawController::class, 'updateIndex'])->name('law.update');
                Route::post('/update', [LawController::class, 'update']);
                Route::get('/show/{id}', [LawController::class, 'show'])->name('law.show');
                Route::post('/delete', [LawController::class, 'delete']);
                Route::get('/showHistory/{id}', [LawController::class, 'showHistory'])->name('laws.history.show');
                Route::get('/GetLawInfo', [LawController::class, 'getLawInfo']);
                Route::post('/RemoveRefer', [LawController::class, 'removeRefer']);
            });

            //Start Reports
            //Excel Reports
            Route::get('/ExcelAllReports', [ExcelAllReportsController::class, 'index']);
            Route::get('/GetReport', [ExcelAllReportsController::class, 'getReport'])->name('GetReport');

            //PDF Reports
            Route::get('/PDFReports', [PDFReportController::class, 'index']);
            Route::post('/GeneratePDF', [PDFReportController::class, 'generatePDF']);
            //End Reports

            Route::post('/CompareText', [Controller::class, 'compareText']);

            Route::prefix('BackupDatabase')->group(function () {
                Route::get('/', [DatabaseBackupController::class, 'index']);
                Route::post('/', [DatabaseBackupController::class, 'createBackup']);
            });
        });

    });
});

