<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
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

}
