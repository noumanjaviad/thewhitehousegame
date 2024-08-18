<?php

namespace App\Models;

use App\Models\Prediction;
use App\Models\VotterParty;
use App\Models\ChosenPartyCandidate;
use Illuminate\Database\Eloquent\Model;
use App\Models\ChosenPresidentVicePresidentStates;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VoterCandidate extends Model
{
    use HasFactory;
    protected $fillable = ['candidate_name', 'dob', 'candidate_image', 'birth_place', 'occupation', 'occupation_1', 'position', 'position_1', 'votter_party_id', 'order'];

    // protected function Image(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn ($value) =>$value?'storage/'.$value:'',
    //     );
    // }

    public function voters()
    {
        // return $this->belongsToMany(User::class, 'user_candidate_vottings', 'voter_candidate_id', 'user_id');
        return $this->belongsToMany(User::class, 'user_candidate_vottings', 'voter_candidate_id', 'user_id');
    }

    public function party()
    {
        return $this->belongsTo(VotterParty::class, 'votter_party_id');
    }

    public function votes()
    {
        return $this->hasMany(Prediction::class, 'voter_candidate_id');
    }

    public function chosen_candidate()
    {
        return $this->hasMany(ChosenPartyCandidate::class, 'voter_candidate_id');
    }

    public function chose_president_vice_presidents_state()
    {
        return $this->hasMany(ChosenPresidentVicePresidentStates::class, 'voter_candidate_id');
    }

    //for specific scenarios
    public function parties()
    {
        return $this->belongsToMany(VotterParty::class, 'candidate_party');
    }
}
