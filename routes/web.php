<?php
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Insertcontroller;
use App\Http\Controllers\DirectorateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::prefix('directorate')->middleware('role:direct,teacher,admin')->group(function () {
    Route::get('/', function (Request $request) {
        return DirectorateRequest::get($request);
    });
    Route::post('/directorate', function (Request $request) {
        return DirectorateRequest::post($request);
    });
});