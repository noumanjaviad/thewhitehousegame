<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPredictionParty extends Model
{
    use HasFactory;

    protected $fillable=[
        'user_id',
        'votter_party_id',
        'user_state_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function party()
    {
        return $this->belongsTo(VotterParty::class, 'votter_party_id');
    }
    public function votter_party(){
        return $this->belongsTo(VotterParty::class, 'votter_party_id');

    }

    public function state()
    {
        return $this->belongsTo(UserState::class, 'user_state_id');
    }
}
