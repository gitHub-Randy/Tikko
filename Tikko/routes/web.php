<?php

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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::resource('bankaccounts', 'BankAccountController')->middleware('auth');
Route::resource('tikkos', 'TikkoController')->middleware('auth');
Route::post('/tikkos/confirm', 'TikkoController@confirm')->middleware('auth')->name('confirm');
Route::resource('groups', 'GroupsController')->middleware('auth');
Route::resource('payments', 'PaymentController')->middleware('auth');
Route::post('/payments/pay', 'PaymentController@pay')->middleware('auth')->name('pay');
Route::post('/payments/prepare', 'PaymentController@prepare')->middleware('auth')->name('prepare');
Route::post('/webhook', 'PaymentController@MollieHook')->name('webhook');
