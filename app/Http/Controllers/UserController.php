<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\GameSettings;
use App\Models\User;
use App\Services\AnswerService;
use App\Services\OnlineService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(){

        return view('panels.userPanel');
    }

    public function answerCreate(Request $request): array {
        $request->validate([
            'number_variable' => 'nullable'
        ]);

        $typeGame = GameSettings::first()->type_answer;
        $answerService = new AnswerService();

        switch ($typeGame){
            case GameSettings::TYPE_SOLO:
                if(Answer::count() == 0){
                    $answerService->createAnswer($request);
                    return ['success' => true, 'user' => Auth::user()->id];
                }
                break;
            case GameSettings::TYPE_CHOOSE:
                $check = $answerService->checkUserAnswer($request->input('number_variable'));
                if(is_null($check)){
                    return ['success' => false,'user' => Auth::user()->id];
                }
                if($check > 0){
                    $answerService->removeAnswersUser();
                }
                $answerService->createAnswer($request);
                return ['success' => true,'user' => Auth::user()->id];
                break;
        }

        return ['success' => false, 'user' => Auth::user()->id];
    }
}
