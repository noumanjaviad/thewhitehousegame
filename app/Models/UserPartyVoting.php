<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPartyVoting extends Model
{
    use HasFactory;

    

    protected $table="user_party_vottings";

    protected $fillable = [
        'user_id', 'votter_party_id', 'source', 'votting_year','postion',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function votter_party(){
        return $this->belongsTo(VotterParty::class,'votter_party_id');
    }
}
