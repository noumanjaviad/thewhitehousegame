<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\VotterParty;
use Illuminate\Http\Request;
use App\Models\VoterCandidate;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index(){
        $user=User::all()->count();
        $party=VotterParty::all()->count();
        $candidate=VoterCandidate::all()->count();
        return view('Admin.dashboard',compact('user','party','candidate'));
    }
}
