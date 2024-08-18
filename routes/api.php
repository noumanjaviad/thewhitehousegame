<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\EducationController;
use App\Http\Controllers\Api\LanguageController;
use App\Http\Controllers\Api\NewsletterController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\PredictionController;
use App\Http\Controllers\Api\PreviousElectionStateController;
use App\Http\Controllers\Api\UserAgeController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UserCountryBirthController;
use App\Http\Controllers\Api\UserEmployementController;
use App\Http\Controllers\Api\UserEthnicityController;
use App\Http\Controllers\Api\UserGenderController;
use App\Http\Controllers\Api\UserStateController;
use App\Http\Controllers\Api\UserVottingPartyController;
use App\Http\Controllers\Api\VoterCandidateController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
 */

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('throttle:api')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('forget_password', [AuthController::class, 'forget_password']);
    Route::post('match_otp', [AuthController::class, 'match_otp']);
    Route::post('resendOTP', [AuthController::class, 'resendOTP']);
    Route::get('getAllDetails', [BaseController::class, 'getAllDetails']);
    Route::post('joinNewsletter', [NewsletterController::class, 'joinNewsletter']);
    Route::get('getVoterPartyCount', [PredictionController::class, 'getVoterPartyCount']);

    //for filter
    Route::get('filter', [PredictionController::class, 'filter']);
    //end
    // Route::get('getVotesPercentage2020',[PreviousElectionStateController::class,'getVotesPercentage2020']);
    Route::get('getVotesPercentage2020', [PreviousElectionStateController::class, 'getVotespercentagepresentyear']);

    Route::get('ratioVoterCandidateRole', [PredictionController::class, 'ratioVoterCandidateRole']);

    Route::get('get_all_language', [LanguageController::class, 'get_all_language']);
    Route::get('get_all_education', [EducationController::class, 'get_all_education']);
    Route::get('get_user_age', [UserAgeController::class, 'get_user_age']);
    Route::get('get_all_user_country_birth', [UserCountryBirthController::class, 'get_all_user_country_birth']);
    Route::get('get_all_user_employement', [UserEmployementController::class, 'get_all_user_employement']);
    Route::get('get_user_ethnicty', [UserEthnicityController::class, 'get_user_ethnicty']);
    Route::get('get_user_gender', [UserGenderController::class, 'get_user_gender']);
    Route::get('get_user_state', [UserStateController::class, 'get_user_state']);

    //befor chose presendient and voice presedent of party this will display candidate belong to party.
    Route::get('get_votter_candidate', [VoterCandidateController::class, 'VoterCandidates']);
    Route::get("voter_candidate_list", [VoterCandidateController::class, 'VoterCandidateList']);

});

Route::middleware(['auth:api', 'token.expired'])->group(function () {
    //for test purpose to check it work or not
    Route::get('get_user', [AuthController::class, 'get_user']);
    Route::post('rest_password', [AuthController::class, 'rest_password']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('update_user_info', [UserController::class, 'update_user_info']);
    Route::get('get_user_info', [UserController::class, 'getUserInfo']);
    Route::post('change_user_profile_info/{id}', [UserController::class, 'changeUserProfileInfo']);
    Route::get('get_user_language/{id}', [UserController::class, 'getUserLanguage']);
    Route::post('upload_profile_image', [UserController::class, 'uploadProfileImage']);

    Route::post('processPayment', [PaymentController::class, 'processPayment']);

    Route::get('getFinalizeCandidateElectroral', [PredictionController::class, 'getFinalizeCandidateElectroral']);

    //for chosing presedent and voice president
    // Route::post('select_party_leaders',[PredictionController::class,'selectPartyLeaders']);

    //update chosen presedent and voice president
    Route::post('select_party_leaders', [PredictionController::class, 'chosePartyLeaders']);
    //end

    //chose prediction party in 51 state
    Route::post('submit_electoral_college_prediction', [PredictionController::class, 'submitElectoralCollegePrediction']);
    //for mobile app without app
    Route::post('submit_electoral_college_prediction_app', [PredictionController::class,'submitElectoralCollegePredictionApp']);
    //end
    Route::get('getStateParty', [PredictionController::class, 'getStateParty']);

    //for chose user party
    Route::get('get_votter_parties', [UserVottingPartyController::class, 'getVotterParties']);
    Route::post('chose_party', [UserVottingPartyController::class, 'choseVotterParty']);

    Route::get("get_candidate/{id}", [VoterCandidateController::class, 'getCandidate']);
    Route::get("get_predict_party_candidate", [PredictionController::class, 'getPredictedPartyCandidate']);
    Route::post("predict_party_leader", [PredictionController::class, 'predictPartyLeaders']);

    //for mobile app without pay
    Route::post('predictPartyLeadersApp',[PredictionController::class, 'predictPartyLeadersApp']);
    //end

    //
    Route::get('getCandidateparty/{id}', [UserVottingPartyController::class, 'getCandidateparty']);

    Route::get('userVotings', [PredictionController::class, 'userVotings']);
});
//dummy api for storing state image
Route::post('/user_states/{id}', [UserStateController::class, 'update']);
Route::post('votter_candidate_detail', [VoterCandidateController::class, 'VoterCandidateDetails']);

Route::post('create_previous_election_state', [PreviousElectionStateController::class, 'createPreviousElectionState']);
Route::post('update_voter_candidate/{id}', [VoterCandidateController::class, 'updateVoterCandidate']);
