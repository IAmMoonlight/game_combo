<?php

namespace App\Services;

use App\Models\Answer;
use Illuminate\Support\Facades\Auth;

class AnswerService
{
    public function removeAnswersUser(): void {
        if(!Auth::check()){
            return;
        }
        Answer::where('user_id','=',Auth::user()->id)->delete();
    }
    public function removeAll(): void {
        Answer::query()->delete();
    }
    public function checkUserAnswer($value): ?int {
        if(!Auth::check()){
            return null;
        }
        $isSameAnswer = Answer::where('user_id','=',Auth::user()->id)
            ->where('number_variable','=',$value)
            ->count();
        if($isSameAnswer){
            return null;
        }

        return Answer::where('user_id','=',Auth::user()->id)->count();
    }
    public function createAnswer($request): void {
        Answer::create([
            'user_id' => Auth::user()->id,
            'number_variable' => $request->input('number_variable')
        ]);
    }
}
