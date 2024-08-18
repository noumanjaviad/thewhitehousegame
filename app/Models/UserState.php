<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ChosenPresidentVicePresidentStates;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserState extends Model
{
    use HasFactory;

    protected $fillable=[
        'image_url',
        'name',
        'state_image_url',
        'map_url'
    ];

    public function users(){
        return $this->hasMany(User::class);
    }

    public function partyPredictions()
    {
        return $this->hasMany(UserPredicationParty::class, 'user_state_id');
    }

    public function PredictionsPresidentVicePresidentStates()
    {
        return $this->hasMany(ChosenPresidentVicePresidentStates::class, 'user_state_id');
    }

    public function previous_election_state(){
        return $this->hasMany(PreviousElectionState::class);
    }
}
