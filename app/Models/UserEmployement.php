<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserEmployement extends Model
{
    use HasFactory;

    protected $fillable=['employement_status'];

    public function users(){
        return $this->hasMany(User::class);
    }
}
