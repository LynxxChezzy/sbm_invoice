<?php

namespace App\Http\Controllers;

use App\Models\Kwitansi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    public function pdf($id)
    {
        // Retrieve kwitansi data with perusahaan and kontakPerusahaan
        $kwitansi = Kwitansi::with(['perusahaan.kontakPerusahaan', 'uraianGas'])->findOrFail($id);

        // Convert the logo image to Base64
        $logoPath = public_path('img/Logo_SBM2.png');
        $logoBase64 = base64_encode(file_get_contents($logoPath));
        $logoMimeType = mime_content_type($logoPath); // e.g., "image/png"

        // Prepare data for the view
        $data = [
            'kwitansi' => $kwitansi,
            'total' => $kwitansi->total,
            'perusahaanNama' => $kwitansi->perusahaan->nama, // Correctly fetch the name
            'kontakPerusahaan' => $kwitansi->perusahaan->kontakPerusahaan, // Include contacts
            'uraianGas' => $kwitansi->uraianGas,
            'logoBase64' => "data:$logoMimeType;base64,$logoBase64",
        ];

        // Clean the filename to remove invalid characters
        $filename = 'Kwitansi-' . preg_replace('/[\/\\\\]/', '-', $kwitansi->nomor) . '.pdf';

        // Render the PDF
        $pdf = Pdf::loadView('pdf.kwitansi', $data);

        // Stream the PDF
        return $pdf->stream($filename);
    }
}
