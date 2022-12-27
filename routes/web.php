<?php

use Illuminate\Support\Facades\Route;

use App\Models\User;
use App\Models\Address;
use App\Http\Controllers\GoogleContactsController;

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

Route::get('/contacts', function () {
    return view('AddContacts');
});

//Route::any('/CreateContacts', 'GoogleContactsController@addGoogleContact'); 

Route::get('/CreateContacts', [GoogleContactsController::class, 'addGoogleContact']);
Route::get('/CreateContacts2', [GoogleContactsController::class, 'addGoogleContact2']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
