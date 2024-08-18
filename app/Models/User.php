<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\ChosenPartyCandidate;
use App\Models\ChosenPresidentVicePresidentStates;
use App\Models\Prediction;
// use Laravel\Sanctum\HasApiTokens;
use App\Models\UserGender;
use App\Models\UserPartyVoting;
use App\Models\UserPredictionParty;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'language_id',
        'user_age_id',
        'user_state_id',
        'user_ethnicity_id',
        'user_country_birth_id',
        'user_employement_id',
        'user_gender_id',
        'education_id',
        'is_veteran',
        'is_votted_2020',
        'is_subscription_newsletter',
        'dob',
        'profile_image',
        'email_verified_at',
        'otp',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id', 'id');
    }
    public function user_language()
    {
        return $this->belongsTo(Language::class, 'language_id', 'id');
    }

    public function age()
    {
        return $this->belongsTo(UserAge::class, 'user_age_id', 'id');
    }
    public function user_age()
    {
        return $this->belongsTo(UserAge::class, 'user_age_id', 'id');
    }

    public function state()
    {
        return $this->belongsTo(UserState::class, 'user_state_id', 'id');
    }
    public function user_state()
    {
        return $this->belongsTo(UserState::class, 'user_state_id', 'id');
    }

    public function ethnicity()
    {
        return $this->belongsTo(UserEthnicity::class, 'user_ethnicity_id', 'id');
    }
    public function user_ethnicity()
    {
        return $this->belongsTo(UserEthnicity::class, 'user_ethnicity_id', 'id');
    }

    public function user_country_birth()
    {
        return $this->belongsTo(UserCountryBirth::class, 'user_country_birth_id', 'id');
    }

    public function employement()
    {
        return $this->belongsTo(UserEmployement::class, 'user_employement_id', 'id');
    }
    public function user_employement()
    {
        return $this->belongsTo(UserEmployement::class, 'user_employement_id', 'id');
    }

    public function gender()
    {
        return $this->belongsTo(UserGender::class, 'user_gender_id');
    }
    public function user_gender()
    {
        return $this->belongsTo(UserGender::class, 'user_gender_id');
    }

    public function education()
    {
        return $this->belongsTo(Education::class, 'education_id', 'id');
    }
    public function user_education()
    {
        return $this->belongsTo(Education::class, 'education_id', 'id');
    }

    public function voted_candidates()
    {
        return $this->belongsToMany(VoterCandidate::class, 'user_candidate_vottings', 'user_id', 'voter_candidate_id');
    }
    public function user_voted_candidates()
    {
        return $this->belongsToMany(VoterCandidate::class, 'user_candidate_vottings', 'user_id', 'voter_candidate_id');
    }
    public function users_voted_candidates()
    {
        return $this->belongsToMany(VoterCandidate::class, 'user_candidate_vottings', 'user_id', 'voter_candidate_id')
            ->withPivot('source');
    }

    public function user_party_vottings()
    {
        return $this->hasMany(UserPartyVoting::class);
    }

    public function partyPredictions()
    {
        return $this->hasMany(UserPredictionParty::class, 'user_id');
    }

    //this is for relation where save dynmicaly president and vice presidents
    //in sates election electroal
    public function Predictions_voice_president_president_states()
    {
        return $this->hasMany(ChosenPresidentVicePresidentStates::class, 'user_id');
    }

    public function user_partyPredictions()
    {
        return $this->hasMany(UserPredictionParty::class, 'user_id');
    }

    public function user_predictions()
    {
        return $this->hasMany(Prediction::class, 'user_id');
    }

    public function chosen_party_candidates()
    {
        return $this->hasMany(ChosenPartyCandidate::class, 'user_id');
    }

    public function user_chosen_party_candidates()
    {
        return $this->hasMany(ChosenPartyCandidate::class, 'user_id');
    }

    // public function user_chosen_president_vice_president_states(){
    //     return $this->hasMany(ChosenPresidentVicePresidentStates::class, 'user_id');
    // }
}
