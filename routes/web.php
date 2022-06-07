<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Product;
use App\Http\Livewire\Cart;
use App\Http\Livewire\Penjualan;
use App\Http\Livewire\Auth\Login;
use App\Http\Livewire\Auth\Register;
use App\Http\Livewire\Auth\Logout;
use App\Http\Controllers\HomeController;


Route::group(['middleware' => 'guest'], function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
    Route::get('/', function () {
        return view('welcome');
    });
});

Route::group(['middleware' => ['auth']], function () {
    Route::get('/products', Product::class);
    Route::get('/cart', Cart::class);
    Route::get('/penjualan', Penjualan::class);
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/cetak/{invoice?}', [HomeController::class, 'cetakNota'])->name('cetak');
    Route::get('/logout', Logout::class)->name('logout');

});

