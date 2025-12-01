<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\UsersExport;
use App\Imports\UsersImport;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class FileController extends Controller
{
    
    public function upload(Request $request)
{
    $validated = $request->validate([
        'file' => 'required|file|mimes:pdf,jpeg,png,jpg,doc,docx|max:5120'
    ], [
        'file.required' => 'Please upload a file',
        'file.mimes' => 'Only JPEG and PNG formats are allowed',
        'file.max' => 'File size must not exceed 5MB'
    ]);

    $path = $request->file('file')->store('uploads', 'public');

    return response()->json([
        'status' => 200,
        'message' => 'File successfully uploaded',
        'file_path' => asset('storage/' . $path),
        'file_name' => basename($path)
    ], 200);
}

public function export()
{

    return Excel::download(new UsersExport,'users.xlsx', \Maatwebsite\Excel\Excel::XLSX);
}

public function uploadExcel(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:xlsx,xls,csv|max:5120'
    ], [
        'file.required' => 'Please upload a file',
        'file.mimes' => 'Only Excel/CSV files are allowed',
        'file.max' => 'File size must not exceed 5MB'
    ]);

    Excel::import(new UsersImport, $request->file('file'));

    return redirect()->back()->with('success', 'Users imported successfully!');
}

public function view_pdf(){

    $users = User::all();
    $pdf = Pdf::loadView('users_pdf',array('users' => $users));
    return $pdf->stream();
}

public function print_pdf(){

    $users = User::all();
    $pdf = Pdf::loadView('users_pdf',array('users' => $users));
    return $pdf->download('users_list.pdf');
}

}
