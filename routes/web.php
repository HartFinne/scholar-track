<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScholarController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StaffAuthController;


Route::view('/', 'mainhome')->name('mainhome');
Route::view('roleselection', 'roleselection')->name('roleselection');

// routing for scholars page just for viewing the page no logic used here
Route::prefix('scholar')->middleware('scholar')->group(function () {
    Route::view('/csform', 'scholar.csform')->name('csform');
    Route::view('/csactivities', 'scholar.csactivities')->name('csactivities');
    Route::view('/csattendance', 'scholar.csattendance')->name('csattendance');
    Route::view('/csdashboard', 'scholar.csdashboard')->name('csdashboard');
    Route::view('/csdetails', 'scholar.csdetails')->name('csdetails');
    Route::view('/gradesinfo', 'scholar.gradesinfo')->name('gradesinfo');
    Route::view('/gradesub', 'scholar.gradesub')->name('gradesub');
    Route::view('/lteform', 'scholar.lteform')->name('lteform');
    Route::view('/lteinfo', 'scholar.lteinfo')->name('lteinfo');
    Route::view('/schumanities', 'scholar.schumanities')->name('schumanities');
    Route::view('/sclte', 'scholar.sclte')->name('sclte');
    Route::view('/overview', 'scholar.overview')->name('overview');
    Route::view('/sublteinfo', 'scholar.sublteinfo')->name('subtleinfo');
    Route::view('/screnewal', 'scholar.screnewal')->name('screnewal');
    Route::view('/subrenewal', 'scholar.subrenewal')->name('subrenewal');
    Route::view('/schome', 'scholar.schome')->name('schome');
    Route::view('/manageprofile', 'scholar.manageprofile')->name('manageprofile');
});

// routing still for scholars page forms with logic to send to the database

// route to registration
Route::view('/registration', 'registration')->name('registration');
Route::post('/registerScholar', [HomeController::class, 'registerScholar'])->name('registerScholar');

Route::prefix('scholar')->controller(LoginController::class)->group(function () {
    Route::get('/scholar-login', 'viewLogin')->name('scholar-login');
    Route::post('/scholar-login', 'authLogin')->name('scholar-login.post'); // For handling the form submission
    Route::post('/logout', 'logout')->name('logout');
});

Route::prefix('staff')->controller(StaffController::class)->group(function () {
    Route::get('/accountsw', 'showAccountSW')->name('account-sw');
    Route::get('/accountsa', 'showAccountSA')->name('account-sa');
    Route::get('/applicants', 'showApplicants')->name('applicants');
    Route::get('/applicationforms', 'showApplicationForms')->name('applicationforms');
    Route::get('/closedevents', 'showCSClosedEvents')->name('communityservice-closed');
    Route::get('/hcattendancesystem', 'showAttendanceSystem')->name('attendancesystem');
    Route::get('/home', 'showHome')->name('home-sw');
    Route::get('/login', 'showLogin')->name('login-sw');
    Route::get('/listcollege', 'showScholarsCollege')->name('scholars-college');
    Route::get('/listelementary', 'showScholarsElem')->name('scholars-elementary');
    Route::get('/listhighschool', 'showScholarsHS')->name('scholars-highschool');
    Route::get('/lte', 'showLTE')->name('lte');
    Route::get('/managecs', 'showCommunityService')->name('communityservice');
    Route::get('/managehc', 'showHumanitiesClass')->name('humanitiesclass');
    Route::get('/openevents', 'showCSOpenEvents')->name('communityservice-open');
    Route::get('/penalty', 'showPenalty')->name('penalty');
    Route::get('/qualificationcollege', 'showQualiCollege')->name('qualification-college');
    Route::get('/qualificationelem', 'showQualiElem')->name('qualification-elementary');
    Route::get('/qualificationjhs', 'showQualiJHS')->name('qualification-juniorhigh');
    Route::get('/qualificationshs', 'showQualiSHS')->name('qualification-seniorhigh');
    Route::get('/regularallowance', 'showAllowanceRegular')->name('allowancerequests-regular');
    Route::get('/renewal', 'showRenewal')->name('scholarshiprenewal');
    Route::get('/renewcollege', 'showRenewalCollege')->name('renewal-college');
    Route::get('/renewelementary', 'showRenewalElem')->name('renewal-elementary');
    Route::get('/renewhighschool', 'showRenewalHS')->name('renewal-highschool');
    Route::get('/scholars', 'showScholars')->name('scholars-overview');
    Route::get('/specialallowance', 'showAllowanceSpecial')->name('allowancerequests-special');
    Route::get('/admdashboard', 'showDashboard')->name('dashboard');
    Route::get('/admscholars', 'showUsersScholar')->name('users-scholar');
    Route::get('/admstaff', 'showUserStaff')->name('users-staff');
    Route::get('/admstaff/userinfo/{id}', 'showUserInfo')->name('staff.view');
    Route::get('/admapplicants', 'showUserApplicants')->name('users-applicant');
    Route::post('/admstaff/activate/{id}', 'activateUser')->name('staff.activate');
    Route::post('/admstaff/deactivate/{id}', 'deactivateUser')->name('staff.deactivate');
});

Route::prefix('staff')->controller(StaffAuthController::class)->group(function () {
    Route::post('/login', 'login')->name('login');
    Route::get('/logout', 'logout')->name('logout-sw');
    Route::post('/admstaff', 'createAccount')->name('staccount.create');
});
