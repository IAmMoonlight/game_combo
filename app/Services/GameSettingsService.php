<?php

namespace App\Services;

use App\Models\GameSettings;

class GameSettingsService
{
    public function getTypeAnswer(): string {
        return GameSettings::first()->type_answer;
    }
    public function getStatusPlay(): bool {
        return (bool)GameSettings::first()->is_play;
    }

    public function switchType(string $gameType){
        $result = null;
        $gameSettingsFirst = GameSettings::first();
        $gameSettingsFirst->update([
            'type_answer' => $gameType
        ]);
        $result = $gameType;
//        if($gameSettingsFirst->type_answer == GameSettings::TYPE_CHOOSE){
//            $gameSettingsFirst->update([
//                'type_answer' => GameSettings::TYPE_SOLO
//            ]);
//            $result = GameSettings::TYPE_SOLO;
//        }else{
//            $gameSettingsFirst->update([
//                'type_answer' => GameSettings::TYPE_CHOOSE
//            ]);
//            $result = GameSettings::TYPE_CHOOSE;
//        }
        return $result;
    }

    public function playPause(){
        $gameSettingsFirst = GameSettings::first();
        if($gameSettingsFirst->is_play){
            $gameSettingsFirst->update([
                'is_play' => false
            ]);
        }else{
            $gameSettingsFirst->update([
                'is_play' => true
            ]);
        }
        return $gameSettingsFirst->is_play;
    }
}
