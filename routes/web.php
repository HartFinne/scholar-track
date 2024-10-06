<?php

use Illuminate\Support\Facades\Route;


Route::view('/', 'mainhome')->name('mainhome');
Route::view('registration', 'registration')->name('registration');
Route::view('roleselection', 'roleselection')->name('roleselection');

Route::prefix('scholar')->group(function () {
    Route::view('/login', 'scholar.login')->name('sclogin');
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

Route::prefix('staff')->group(function () {
    Route::view('/account', 'staff.account')->name('staccount');
    Route::view('/applicants', 'staff.applicants')->name('stapplicants');
    Route::view('/applicationforms', 'staff.applicationforms')->name('stapplicationforms');
    Route::view('/closedevents', 'staff.closedevents')->name('stclosedevents');
    Route::view('/hcattendancesystem', 'staff.hcattendancesystem')->name('sthcattendancesystem');
    Route::view('/home', 'staff.home')->name('sthome');
    Route::view('/listcollege', 'staff.listcollege')->name('stlistcollege');
    Route::view('/listelementary', 'staff.listelementary')->name('stlistelementary');
    Route::view('/listhighschool', 'staff.listhighschool')->name('stlisthighschool');
    Route::view('/login', 'staff.login')->name('stlogin');
    Route::view('/lte', 'staff.lte')->name('stlte');
    Route::view('/managecs', 'staff.managecs')->name('stmanagecs');
    Route::view('/managehc', 'staff.managehc')->name('stmanagehc');
    Route::view('/openevents', 'staff.openevents')->name('stopenevents');
    Route::view('/penalty', 'staff.penalty')->name('stpenalty');
    Route::view('/qualificationcollege', 'staff.qualificationcollege')->name('stqualificationcollege');
    Route::view('/qualificationelem', 'staff.qualificationelem')->name('stqualificationelem');
    Route::view('/qualificationjhs', 'staff.qualificationjhs')->name('stqualificationjhs');
    Route::view('/qualificationshs', 'staff.qualificationshs')->name('stqualificationshs');
    Route::view('/regularallowance', 'staff.regularallowance')->name('stregularallowance');
    Route::view('/renewal', 'staff.renewal')->name('strenewal');
    Route::view('/renewcollege', 'staff.renewcollege')->name('strenewcollege');
    Route::view('/renewelementary', 'staff.renewelementary')->name('strenewelementary');
    Route::view('/renewhighschool', 'staff.renewhighschool')->name('strenewhighschool');
    Route::view('/scholars', 'staff.scholars')->name('stscholars');
    Route::view('/specialallowance', 'staff.specialallowance')->name('stspecialallowance');
});
