<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
use App\Http\Controllers\ZodiacSignsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('layouts.app');
});

Route::resource('zodiacsigns', ZodiacSignsController::class);

Route::any('/uploaded-files/file-info', 'App\Http\Controllers\UploadController@file_info')->name('uploaded-files.info');
Route::resource('/uploaded-files', 'App\Http\Controllers\UploadController');
Route::get('/uploaded-files/destroy/{id}', 'App\Http\Controllers\UploadController@destroy')->name('uploaded-files.destroy');

Route::post('/aiz-uploader', 'App\Http\Controllers\UploadController@show_uploader');
Route::post('/aiz-uploader/upload', 'App\Http\Controllers\UploadController@upload');
Route::get('/aiz-uploader/get_uploaded_files', 'App\Http\Controllers\UploadController@get_uploaded_files');
Route::post('/aiz-uploader/get_file_by_ids', 'App\Http\Controllers\UploadController@get_preview_files');
Route::get('/aiz-uploader/download/{id}', 'App\Http\Controllers\UploadController@attachment_download')->name('download_attachment');
