<?php

use App\Http\Controllers\PdfController;
use Illuminate\Support\Facades\Route;

Route::get('/cetak/{id}', [PdfController::class, 'pdf'])->name('kwitansi.cetak');
