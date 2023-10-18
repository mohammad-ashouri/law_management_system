<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\EquipmentLog;
use App\Models\Person;
use Illuminate\Http\Request;
use niklasravnsborg\LaravelPdf\Facades\Pdf;

class PDFReportController extends Controller
{
    public function index()
    {
        return view('Reports.PDFReports');
    }
}
