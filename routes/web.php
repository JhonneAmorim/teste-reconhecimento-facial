<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;

Route::get('/', function () {
    return view('faceapi');
});

Route::post('/save-face', [
    ImageController::class,
    'saveFace'
])->name('save.face');

Route::get('/success', [
    ImageController::class,
    'showSuccessPage'
]);
