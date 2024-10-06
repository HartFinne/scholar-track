<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScholarController;


Route::view('/', 'mainhome')->name('mainhome');
Route::view('roleselection', 'roleselection')->name('roleselection');


// route to registration
Route::view('registration', 'registration')->name('registration');
Route::post('registerScholar', [HomeController::class, 'registerScholar'])->name('registerScholar');



// routing for scholars page just for viewing the page no logic used here
Route::prefix('scholar')->group(function () {
    Route::view('/csform', 'scholar.csform')->name('csform');
    Route::view('/csactivities', 'scholar.csactivities')->name('csactivities');
    Route::view('/csattendance', 'scholar.csattendance')->name('csattendance');
    Route::view('/csdashboard', 'scholar.csdashboard')->name('csdashboard');
    Route::view('/csdetails', 'scholar.csdetails')->name('csdetails');
    Route::view('/gradesinfo', 'scholar.gradesinfo')->name('gradesinfo');
    Route::view('/gradesub', 'scholar.gradesub')->name('gradesub');
    Route::view('/lteform', 'scholar.lteform')->name('lteform');
    Route::view('/lteinfo', 'scholar.lteinfo')->name('lteinfo');
    Route::view('/schome', 'scholar.schome')->name('schome');
    Route::view('/schumanities', 'scholar.schumanities')->name('schumanities');
    Route::view('/sclte', 'scholar.sclte')->name('sclte');
    Route::view('/overview', 'scholar.overview')->name('overview');
    Route::view('/manageprofile', 'scholar.manageprofile')->name('manageprofile');
});


// routing still for scholars page forms with logic to send to the database

Route::prefix('scholar')->controller(ScholarController::class)->group(function () {});
