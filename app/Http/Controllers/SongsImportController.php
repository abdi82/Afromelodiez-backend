<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Song;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SongsImport;

class SongsImportController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        try {
            Excel::import(new SongsImport(), $request->file('file'));
        } catch (\Exception $e) {
            \Log::error('Error importing songs: ' . $e->getMessage());
        }
        return redirect()->back()->with('status', 'Songs imported successfully!');
    }
}
