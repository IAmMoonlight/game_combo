<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class OnlineService
{
    public function updateLastVisit(){
        User::find(Auth::user()->id)->update([
           'last_visit' => Carbon::now()
        ]);
    }

    public static function isOnline(User $user){
        $diff = Carbon::now()->diffInMinutes($user->last_visit);
        if($diff < 15){
            return true;
        }else{
            return false;
        }
    }
}
