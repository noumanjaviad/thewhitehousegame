<?php

namespace App\Models;

use App\Models\User;
use App\Models\UserState;
use App\Models\VotterParty;
use App\Models\VoterCandidate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChosenPresidentVicePresidentStates extends Model
{
    use HasFactory;

    protected $fillable=[
        'user_id',
        'voter_candidate_id',
        'votter_party_id' ,
        'position',
    ];

    public function voters()
    {
        return $this->belongsTo(User::class, 'user_candidate_vottings', 'voter_candidate_id', 'user_id');
    }

    public function party()
    {
        return $this->belongsTo(VotterParty::class, 'votter_party_id');
    }
    public function voted_candidates()
    {
        return $this->belongsTo(VoterCandidate::class,'voter_candidate_id');
    }

    public function state()
    {
        return $this->belongsTo(UserState::class, 'user_state_id');
    }

}
