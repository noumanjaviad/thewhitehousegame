<?php

namespace App\Models;

use App\Models\User;
use App\Models\VotterParty;
use App\Models\VoterCandidate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChosenPartyCandidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'voter_candidate_id',
        'votter_party_id' ,
        'position',
    ];

    public function user() {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function voter_candidate() {
        return $this->belongsTo(VoterCandidate::class,'voter_candidate_id','id');
    }

    public function votter_party() {
        return $this->belongsTo(VotterParty::class,'votter_party_id','id');
    }
}
