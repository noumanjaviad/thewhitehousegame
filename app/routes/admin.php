<?php

use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\StateController;
use App\Http\Controllers\Admin\CandidateController;
use App\Http\Controllers\Admin\VoterPartyController;
use App\Http\Controllers\Admin\EmployementController;
use App\Http\Controllers\Admin\UserEthnicityController;
use App\Http\Controllers\Admin\UserCountryBirthController;
use App\Http\Controllers\Admin\PreviousElectionStateController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('admin/dashboard',[HomeController::class,'index'])->name('admin.dashboard');
Route::post('admin/candidate/candidate_delete',[CandidateController::class,'candidate_delete'])->middleware(['auth'])->name('candidate_delete');
Route::get('admin/candidate/get_all_candidates',[CandidateController::class,'get_all_candidates'])->middleware(['auth'])->name('get_all_candidates');
Route::resource('admin/candidate',CandidateController::class)->middleware(['auth']);

Route::resource('admin/parties', VoterPartyController::class)->middleware(['auth']);
Route::post('admin/parties/party_delete',[VoterPartyController::class,'party_delete'])->middleware(['auth'])->name('party_delete');
Route::get('test',[VoterPartyController::class,'test'])->name('test');

Route::resource('admin/state', StateController::class)->middleware(['auth']);
Route::post('admin/parties/state_delete',[StateController::class,'state_delete'])->middleware(['auth'])->name('state_delete');
Route::get('get_all_state',[StateController::class,'get_all_states'])->name('get_all_states');

Route::resource('admin/ucb', UserCountryBirthController::class)->middleware(['auth']);
Route::post('admin/ucb/ucb_delete',[UserCountryBirthController::class,'ucb_delete'])->middleware(['auth'])->name('ucb_delete');
Route::get('get_all_ucb',[UserCountryBirthController::class,'get_all_ucb'])->name('get_all_ucb');

Route::resource('admin/ethnicity', UserEthnicityController::class);
Route::post('admin/ethnicity/ethnicity_delete',[UserEthnicityController::class,'ethnicity_delete'])->middleware(['auth'])->name('ethnicity_delete');
Route::get('get_all_ethnicity',[UserEthnicityController::class,'get_all_ethnicity'])->name('get_all_ethnicity');

Route::resource('admin/employement', EmployementController::class);
Route::post('admin/employement/employement_delete',[EmployementController::class,'employement_delete'])->middleware(['auth'])->name('employement_delete');
Route::get('get_all_employement',[EmployementController::class,'get_all_employement'])->name('get_all_employement');

Route::resource('admin/previous_election', PreviousElectionStateController::class);
Route::post('admin/previous_election/previous_election_delete',[PreviousElectionStateController::class,'previous_election_delete'])->middleware(['auth'])->name('previous_election_delete');
Route::get('get_all_previous_election',[PreviousElectionStateController::class,'get_all_previous_election'])->name('get_all_previous_election');

