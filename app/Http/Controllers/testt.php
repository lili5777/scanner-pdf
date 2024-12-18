<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Smalot\PdfParser\Parser;

class testt extends Controller
{
    public function upload(Request $request)
    {
        // Validasi file
        $request->validate([
            'pdf' => 'required|mimes:pdf|max:2048',
        ]);

        // Simpan file PDF
        $path = $request->file('pdf')->store('uploads');

        // Parse PDF menggunakan smalot/pdfparser
        $parser = new Parser();
        $pdf = $parser->parseFile(storage_path('app/' . $path));
        $pdfText = $pdf->getText();

        // Regex untuk ekstrak nama
        $matches = [];
        preg_match('/Nim(?:\s*):(?:\s*)(.+)/i', $pdfText, $matches);
        $matches2 = [];
        preg_match('/Nama(?:\s*):(?:\s*)(.+)/i', $pdfText, $matches2);
        $nim = $matches[1] ?? 'Nim tidak ditemukan';
        $name = $matches2[1] ?? 'Nama tidak ditemukan';

        // Kirim nama ke view
        return view('form', compact('name', 'nim'));
    }
}
