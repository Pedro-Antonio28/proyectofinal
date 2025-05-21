<?php

namespace App\Http\Controllers;

use App\Exports\PostsExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
public function exportExcel()
{
return Excel::download(new PostsExport, 'dietas.xlsx');
}
}
