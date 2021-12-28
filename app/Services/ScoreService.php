<?php

namespace App\Services;

use App\Models\ScoreUser;
use Illuminate\Http\Request;

class ScoreService
{
    public function changeScore(Request $request): void {
        $request->validate([
            'value' => 'required|integer',
            'user_id' => 'required|integer',
            'method' => 'required|string'
        ]);

        if($request->input('method') == 'add'){
            $this->incrementScore($request->input('value'), $request->input('user_id'));
        }else{
            $this->decrementScore($request->input('value'), $request->input('user_id'));
        }
    }

    public function incrementScore(int $value, int $user): void {
        $currentScore = ScoreUser::where('user_id','=',$user)->first();
        $currentScore->update([
            'score' => $currentScore->score + $value
        ]);
    }
    public function decrementScore(int $value, int $user): void {
        $currentScore = ScoreUser::where('user_id','=',$user)->first();
        $currentScore->update([
            'score' => $currentScore->score - $value
        ]);
    }
}
