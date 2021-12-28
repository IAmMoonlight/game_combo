<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\GameSettings;
use App\Models\User;
use App\Services\AnswerService;
use App\Services\GameSettingsService;
use App\Services\OnlineService;
use App\Services\ScoreService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index(){
        $listGamers = User::where('type','=', 'gamer')->get();
        return view('panels.adminPanel', compact('listGamers'));
    }

    public function tableResults(){
        $listGamers = User::where('type','=', 'gamer')->get();
        return view('panels.tableResults', compact('listGamers'));
    }

    public function resetAnswers(): void {
        (new AnswerService())->removeAll();
    }

    public function changeTypeAnswer(Request $request): array {
        return ['typeAnswer' => (new GameSettingsService())->switchType($request->input('gameType'))];
    }

    public function changePlayPause(): array {
        return ['status' => (new GameSettingsService())->playPause()];
    }

    public function changeScore(Request $request): array {
        (new ScoreService())->changeScore($request);
        return ['success' => true];
    }

    public function getMetaData(){
        (new OnlineService())->updateLastVisit();

        $type = (new GameSettingsService())->getTypeAnswer();
        return response()->json([
            'userId' => Auth::user()->id,
            'typeAnswer' => $type,
            'status' => (new GameSettingsService())->getStatusPlay(),
            'canAnswer' => $type == GameSettings::TYPE_SOLO ? Answer::count() == 0 : true
        ]);
    }

    public function getUsersData(): array {
        (new OnlineService())->updateLastVisit();
        $result = [
            'meta' => [],
            'gamers' => []
        ];
        $gamers = User::where('type','=','gamer')->with(['answer','score'])->get();
        $arrGamers = [];
        foreacH($gamers as $gamer){
            $arrGamers[] = [
                'id' => $gamer->id,
                'is_answering' => !is_null($gamer->answer),
                'answering' => !is_null($gamer->answer) ? $gamer->answer->number_variable : null,
                'score' => $gamer->score->score,
                'online' => OnlineService::isOnline($gamer)
            ];
        }

        usort($arrGamers, function($a, $b){
            return ($a['score'] - $b['score']);
        });

        $result['meta']['typeAnswer'] = GameSettings::first()->type_answer;
        $result['meta']['statusPlay'] = (bool)GameSettings::first()->is_play;

        $result['gamers'] = $arrGamers;
        return $result;
    }
}
