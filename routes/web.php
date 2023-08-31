<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\VoteController;
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

Route::get('/', [CandidateController::class, 'index'])->name('candidates.index');
Route::post('/cinetpay/notify', [VoteController::class, 'handleCinetPayNotification'])->name('cinetpay.notify');
Route::post( 'cinetpay/return', [VoteController::class, 'handleCinetPayReturn'])->name('cinetpay.return');

Route::post('/candidates/{id}/vote', [VoteController::class, 'store'])->name('candidates.vote');
Route::get('/candidates/{id}', [CandidateController::class, 'show'])->name('candidates.show');

Route::get('/success', [VoteController::class, 'successPage'])->name('success.page');
Route::get('/failure', [VoteController::class, 'failurePage'])->name('failure.page');
Route::get('/error', [VoteController::class, 'errorPage'])->name('error.page');
