<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FacialRecognitionController;

Route::get('/', [FacialRecognitionController::class, 'showUploadForm']);
Route::post('/upload', [FacialRecognitionController::class, 'uploadImage'])->name('facial-recognition.upload');
Route::get('/camera', [FacialRecognitionController::class, 'showCameraView']);

Route::get('/success', function () {
    return view('success');
})->name('success');
