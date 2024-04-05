<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Catalogs\Assistance;
use App\Models\Catalogs\Cases;
use App\Models\Catalogs\Company;
use App\Models\Catalogs\cpu;
use App\Models\Catalogs\Motherboard;
use App\Models\Catalogs\Power;
use App\Models\Catalogs\Ram;
use App\Models\EquipmentedCase;
use App\Models\EstablishmentPlace;
use App\Models\ExecutivePosition;
use App\Models\Person;
use App\Models\Province;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelAllReportsController extends Controller
{
    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return \view('Reports.ExcelAllReports');
    }
}
