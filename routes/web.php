<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BeneficiaryController;
use App\Http\Controllers\NomineeController;


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

Route::get('/', function () {
    return view('welcome');
});


Route::get('beneficiary', [BeneficiaryController::class, 'create']);
Route::post('beneficiary', [BeneficiaryController::class, 'store']);
Route::get('/nominee/{id}', [NomineeController::class, 'create'])->name('nominee.create');

Route::post('/nominee/{id}', [NomineeController::class,'store'])->name('nominee.store');


// Route::get('/nominee/{id}', [NomineeController::class, 'create']);
// Route::post('/nominee/{id}', [NomineeController::class, 'store']);

// index show create store edit update destroy



