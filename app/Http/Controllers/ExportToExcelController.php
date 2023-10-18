<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExportToExcelController extends Controller
{
    public function exportExcel()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'نام');
        $sheet->setCellValue('B1', 'نام خانوادگی');
        $sheet->setCellValue('A2', 'John');
        $sheet->setCellValue('B2', 'Doe');
        $writer = new Xlsx($spreadsheet);
        $filename = 'example.xlsx';
        $writer->save($filename);

        return response()->download($filename)->deleteFileAfterSend(true);
    }
}
