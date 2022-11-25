<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PixelController;
use App\Http\Controllers\EnablePageController;
use App\Http\Controllers\ProductPagePixelController;
use App\Http\Controllers\CategoryPageController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\GlobalGuardController;
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

Route::middleware(['verify.shopify', 'billable'])->group(function () {

    Route::get('/', function () {
        return view('layouts.index');
    });

    Route::get(
        '/pixel-page',
        [EnablePageController::class, 'index']
    )->name('pixel-page');

    Route::post(
        '/userCreate',
        [PixelController::class, 'userCreate']
    )->name('userCreate');

    Route::get(
        '/productSnippetCreate',
        [ProductPagePixelController::class, 'productSnippetCreate']
    )->name('productSnippetCreate');

    Route::get(
        '/productSnippetDelete',
        [ProductPagePixelController::class, 'snippetDelete']
    )->name('productSnippetDelete');

    Route::get(
        '/pageViewSnippetCreate',
        [EnablePageController::class, 'pageViewSnippetCreate']
    )->name('productSnippetCreate');

    Route::get(
        '/pageViewSnippetDelete',
        [EnablePageController::class, 'pageViewSnippetDelete']
    )->name('pageViewSnippetDelete');

    Route::get(
        '/categorySnippetCreate',
        [CategoryPageController::class, 'categorySnippetCreate']
    )->name('categorySnippetCreate');

    Route::get(
        '/categorySnippetDelete',
        [CategoryPageController::class, 'categorySnippetDelete']
    )->name('categorySnippetDelete');

    Route::get(
        '/checkoutSnippetCreate',
        [CheckoutController::class, 'checkoutSnippetCreate']
    )->name('checkoutSnippetCreate');

    Route::get(
        '/checkoutSnippetDelete',
        [CheckoutController::class, 'checkoutSnippetDelete']
    )->name('checkoutSnippetDelete');

    Route::get(
        '/globalGuardSnippetCreate',
        [GlobalGuardController::class, 'globalGuardSnippetCreate']
    )->name('globalGuardSnippetCreate');

    Route::get(
        '/globalGuardSnippetDelete',
        [GlobalGuardController::class, 'globalGuardSnippetDelete']
    )->name('globalGuardSnippetDelete');

    Route::get(
        '/collectInformation',
        [PixelController::class, 'submitInfo']
    )->name('collectInformation');

    Route::get('/home', function () {
        return view('layouts.index');
    })->name('home');

});
