<?php

use App\Http\Controllers\Scholar\HomeController;
use App\Http\Controllers\Scholar\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Scholar\ScholarController;
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
    Route::view('/lteform', 'scholar.lteform')->name('lteform');
    Route::view('/lteinfo', 'scholar.lteinfo')->name('lteinfo');
    Route::view('/schumanities', 'scholar.schumanities')->name('schumanities');
    Route::view('/sclte', 'scholar.sclte')->name('sclte');
    Route::view('/sublteinfo', 'scholar.sublteinfo')->name('subtleinfo');
    Route::view('/screnewal', 'scholar.screnewal')->name('screnewal');
    Route::view('/subrenewal', 'scholar.subrenewal')->name('subrenewal');
    Route::view('/schome', 'scholar.schome')->name('schome');

    // fixed about 70%
    Route::get('/overview', [ScholarController::class, 'showScholarshipOverview'])->name('overview');
    Route::get('/gradesub', [ScholarController::class, 'showGradeSubmission'])->name('gradesub');
    // nag skip ako dito
    Route::post('/gradesub', [ScholarController::class, 'storeGradeSubmission'])->name('gradesub.post');


    Route::get('/manageprofile', [ScholarController::class, 'showProfile'])->name('manageprofile');
    Route::post('/manageprofile', [ScholarController::class, 'updateProfile'])->name('manageprofile.post');

    Route::get('/changepassword', [ScholarController::class, 'changePassword'])->name('changepassword');
});


Route::view('chartjs', 'chartjs');


// routing still for scholars page forms with logic to send to the database

// route to registration
Route::view('/registration', 'registration')->name('registration');
Route::post('/registerScholar', [HomeController::class, 'registerScholar'])->name('registerScholar');

Route::prefix('scholar')->controller(LoginController::class)->group(function () {
    Route::get('/scholar-login', 'viewLogin')->name('scholar-login');
    Route::post('/scholar-login', 'authLogin')->name('scholar-login.post'); // For handling the form submission
    Route::post('/logout', 'logout')->name('logout');
});





Route::prefix('staff')->group(function () {
    Route::get('/accountsw', [StaffController::class, 'showAccountSW'])->name('account-sw');
    Route::get('/accountsa', [StaffController::class, 'showAccountSA'])->name('account-sa');
    Route::get('/applicants', [StaffController::class, 'showApplicants'])->name('applicants');
    Route::get('/applicationforms', [StaffController::class, 'showApplicationForms'])->name('applicationforms');
    Route::get('/closedevents', [StaffController::class, 'showCSClosedEvents'])->name('communityservice-closed');
    Route::get('/hcattendancesystem', [StaffController::class, 'showAttendanceSystem'])->name('attendancesystem');
    Route::get('/home', [StaffController::class, 'showHome'])->name('home-sw');
    Route::get('/listcollege', [StaffController::class, 'showScholarsCollege'])->name('scholars-college');
    Route::get('/listelementary', [StaffController::class, 'showScholarsElem'])->name('scholars-elementary');
    Route::get('/listhighschool', [StaffController::class, 'showScholarsHS'])->name('scholars-highschool');
    Route::get('/login', [StaffController::class, 'showLogin'])->name('login-sw');
    Route::post('/login', [StaffAuthController::class, 'login'])->name('login');
    Route::get('/lte', [StaffController::class, 'showLTE'])->name('lte');
    Route::get('/managecs', [StaffController::class, 'showCommunityService'])->name('communityservice');
    Route::get('/managehc', [StaffController::class, 'showHumanitiesClass'])->name('humanitiesclass');
    Route::get('/openevents', [StaffController::class, 'showCSOpenEvents'])->name('communityservice-open');
    Route::get('/penalty', [StaffController::class, 'showPenalty'])->name('penalty');
    Route::get('/qualificationcollege', [StaffController::class, 'showQualiCollege'])->name('qualification-college');
    Route::get('/qualificationelem', [StaffController::class, 'showQualiElem'])->name('qualification-elementary');
    Route::get('/qualificationjhs', [StaffController::class, 'showQualiJHS'])->name('qualification-juniorhigh');
    Route::get('/qualificationshs', [StaffController::class, 'showQualiSHS'])->name('qualification-seniorhigh');
    Route::get('/regularallowance', [StaffController::class, 'showAllowanceRegular'])->name('allowancerequests-regular');
    Route::get('/renewal', [StaffController::class, 'showRenewal'])->name('scholarshiprenewal');
    Route::get('/renewcollege', [StaffController::class, 'showRenewalCollege'])->name('renewal-college');
    Route::get('/renewelementary', [StaffController::class, 'showRenewalElem'])->name('renewal-elementary');
    Route::get('/renewhighschool', [StaffController::class, 'showRenewalHS'])->name('renewal-highschool');
    Route::get('/scholars', [StaffController::class, 'showScholars'])->name('scholars-overview');
    Route::get('/specialallowance', [StaffController::class, 'showAllowanceSpecial'])->name('allowancerequests-special');
    Route::get('/admdashboard', [StaffController::class, 'showDashboard'])->name('dashboard');
    Route::get('/admscholars', [StaffController::class, 'showUsersScholar'])->name('users-scholar');
    Route::get('/admstaff', [StaffController::class, 'showUserStaff'])->name('users-staff');
    Route::post('/admstaff', [StaffAuthController::class, 'createAccount'])->name('staccount.create');
    Route::get('/admstaff/userinfo/{id}', [StaffController::class, 'showUserInfo'])->name('staff.view');
    Route::post('/admstaff/activate/{id}', [StaffController::class, 'activateUser'])->name('staff.activate');
    Route::post('/admstaff/deactivate/{id}', [StaffController::class, 'deactivateUser'])->name('staff.deactivate');
    Route::get('/admapplicants', [StaffController::class, 'showUserApplicants'])->name('users-applicant');
});
