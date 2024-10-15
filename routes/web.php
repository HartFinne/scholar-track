<?php

use App\Http\Controllers\Scholar\HomeController;
use App\Http\Controllers\Scholar\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Scholar\ScholarController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StaffAuthController;


Route::view('/', 'mainhome')->name('mainhome');
Route::view('roleselection', 'roleselection')->name('roleselection');


//routing for applicant page
Route::prefix('applicant')->group(function () {
    Route::view('/appinstructions', 'applicant.appinstructions')->name('appinstructions');
    Route::view('/applicationformC', 'applicant.applicationformC')->name('form-college');
    Route::view('/applicationformHE', 'applicant.applicationformHE')->name('form-hselem');
    Route::view('/appconfirmdialog', 'applicant.appconfirmdialog')->name('appconfirmdialog');
    Route::view('/applicantportal', 'applicant.applicantportal')->name('applicantportal');
});

// routing for scholars page just for viewing the page no logic used here
Route::prefix('scholar')->middleware('scholar')->group(function () {
    Route::view('/csform', 'scholar.csform')->name('csform');
    Route::view('/csactivities', 'scholar.csactivities')->name('csactivities');
    Route::view('/csattendance', 'scholar.csattendance')->name('csattendance');
    Route::view('/csdashboard', 'scholar.csdashboard')->name('csdashboard');
    Route::view('/csdetails', 'scholar.csdetails')->name('csdetails');
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

    // fixed about 70%
    Route::get('/overview', [ScholarController::class, 'showScholarshipOverview'])->name('overview');

    // -------------------------------------------------------------------------------------------------------------------
    Route::get('/gradesub', [ScholarController::class, 'showGradeSubmission'])->name('gradesub');
    Route::post('/gradesub', [ScholarController::class, 'storeGradeSubmission'])->name('gradesub.post');
    Route::get('/gradesinfo/{id}', [ScholarController::class, 'showGradeInfo'])->name('gradesinfo');
    // fixed na gradesubmission sa page ----------------------------------------------------------------------------------
    // pero dagdagan ng restriction pag nakapaginput na ng 1st sem 2nd sem sa isang academic year


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



Route::prefix('staff')->controller(StaffController::class)->group(function () {
    Route::get('/accountsw', 'showAccountSW')->name('account-sw');
    Route::get('/accountsa', 'showAccountSA')->name('account-sa');
    Route::get('/applicants', 'showApplicants')->name('applicants');
    Route::get('/applicationforms', 'showApplicationForms')->name('applicationforms');
    Route::get('/home', 'showHome')->name('home-sw');
    Route::get('/login', 'showLogin')->name('login-sw');
    Route::get('/listcollege', 'showScholarsCollege')->name('scholars-college');
    Route::get('/listelementary', 'showScholarsElem')->name('scholars-elementary');
    Route::get('/listhighschool', 'showScholarsHS')->name('scholars-highschool');
    Route::get('/scholar/{id}', 'showScholarProfile')->name('scholar-viewinfo');
    Route::get('/lte', 'showLTE')->name('lte');
    // community service
    Route::get('/managecs', 'showCommunityService')->name('communityservice');
    Route::get('/openevents', 'showCSOpenEvents')->name('communityservice-open');
    Route::get('/closedevents', 'showCSClosedEvents')->name('communityservice-closed');
    Route::post('/managecs', 'createcsevent')->name('createcsevent');
    Route::post('/event-info/{csid}', 'updatecsevent')->name('updatecsevent');
    Route::get('/event-info/{csid}', 'showcseventinfo')->name('viewcseventinfo');
    // humanities class
    Route::get('/managehc', 'showHumanitiesClass')->name('humanitiesclass');
    Route::post('/managehc', 'createhc')->name('createhc');
    Route::get('/hcattendancesystem/{hcid}', 'showAttendanceSystem')->name('attendancesystem');
    Route::post('/hcattendancesystem/{hcid}', 'saveattendance')->name('savehcattendance');
    Route::post('/humanitiesclass/{hcid}', 'viewhcattendees')->name('viewhcattendees');
    Route::get('/humanitiesclass/{hcid}-attendees', 'viewattendeeslist')->name('viewattendeeslist');
    Route::post('/managehc/{hcid}', 'exitattendancesystem')->name('exitattendancesystem');

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
    // admin
    Route::get('/admdashboard', 'showDashboard')->name('dashboard');
    Route::get('/admscholars', 'showUsersScholar')->name('users-scholar');
    Route::get('/admstaff', 'showUserStaff')->name('users-staff');
    Route::get('/admapplicants', 'showUserApplicants')->name('users-applicant');
    // user: staff
    Route::get('/staffinfo/{id}', 'showStaffInfo')->name('staff.view');
    Route::post('/staff/activate/{id}', 'activateStaff')->name('staff.activate');
    Route::post('/staff/deactivate/{id}', 'deactivateStaff')->name('staff.deactivate');
    // user: scholar
    Route::get('/scholarinfo/{id}', 'showScholarInfo')->name('scholar.view');
    Route::post('/scholar/activate/{id}', 'activateScholar')->name('scholar.activate');
    Route::post('/scholar/deactivate/{id}', 'deactivateScholar')->name('scholar.deactivate');
});

Route::prefix('staff')->controller(StaffAuthController::class)->group(function () {
    Route::post('/login', 'login')->name('login');
    Route::get('/logout', 'logout')->name('logout-sw');
    Route::post('/admstaff', 'createAccount')->name('staccount.create');
});
