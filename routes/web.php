<?php

use App\Http\Livewire\Offers\CreateOffer;
use App\Http\Livewire\Offers\OfferSentSuccesfully;
use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::get('/offer/{hash:hash}', CreateOffer::class)->name('offer.create');

Route::get('/offer/{hash:hash}/sent-successfully', OfferSentSuccesfully::class)->name('offer.sent-successfully');

Route::get('/prueba', function() {
    return 'view';
});
