<?php

namespace App\Models;

use App\Models\ChosenPartyCandidate;
use App\Models\ChosenPresidentVicePresidentStates;
use App\Models\Prediction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VotterParty extends Model
{
    use HasFactory;

    protected $fillable = ['party_name', 'party_badge'];

    public function voters()
    {
        return $this->belongsToMany(User::class, 'user_party_vottings', 'votter_party_id', 'user_id');
    }

    public function candidates()
    {
        return $this->hasMany(VoterCandidate::class, 'votter_party_id');
    }
    

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function user_party_voting()
    {
        return $this->hasMany(UserPartyVoting::class);
    }

    public function partyPredictions()
    {
        return $this->hasMany(UserPredicationParty::class, 'votter_party_id');
    }

    public function PreseidetVicepresidentPredictions()
    {
        return $this->hasMany(ChosenPresidentVicePresidentStates::class, 'votter_party_id');
    }

    public function previous_election_state()
    {
        return $this->hasMany(PreviousElectionState::class, );
    }

    public function user_voter_party_predictions()
    {
        return $this->hasMany(Prediction::class, 'vottery_party_id');
    }

    public function user_voter_candidate_predictions()
    {
        return $this->hasMany(ChosenPartyCandidate::class, 'votter_candidate_id');
    }

    //for specific scenario
    public function candidate_party()
    {
        return $this->belongsToMany(VoterCandidate::class, 'candidate_party');
    }
}
