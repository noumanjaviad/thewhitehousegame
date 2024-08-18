<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreviousElectionState extends Model
{
    use HasFactory;

    protected $fillable =
        [
        'votter_party_id',
        'election_year',
        'gender',
        'age_range',
        'vote_percentage',
        'male_ratio',
        'female_ratio',
        'user_state_id'
    ];

    public function votter_party(){
        return $this->belongsTo(VotterParty::class);
    }

    public function user_state(){
        return $this->belongsTo(UserState::class);
    }
}
