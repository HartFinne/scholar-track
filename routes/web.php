<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StaffAuthController;


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
    Route::get('/account', [StaffController::class, 'showAccount'])->name('account-sw');
    Route::get('/applicants', [StaffController::class, 'showApplicants'])->name('applicants');
    Route::get('/applicationforms', [StaffController::class, 'showApplicationForms'])->name('applicationforms');
    Route::get('/closedevents', [StaffController::class, 'showCSClosedEvents'])->name('communityservice-closed');
    Route::get('/hcattendancesystem', [StaffController::class, 'showAttendanceSystem'])->name('attendancesystem');
    Route::get('/home', [StaffController::class, 'showHome'])->name('home-sw');
    Route::get('/listcollege', [StaffController::class, 'showScholarsCollege'])->name('scholars-college');
    Route::get('/listelementary', [StaffController::class, 'showScholarsElem'])->name('scholars-elementary');
    Route::get('/listhighschool', [StaffController::class, 'showScholarsHS'])->name('scholars-highschool');
    Route::get('/login', [StaffController::class, 'showLogin'])->name('login-sw');
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
