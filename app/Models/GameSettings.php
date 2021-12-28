<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_answer',
        'is_play'
    ];

    public const TYPE_SOLO = 'solo';
    public const TYPE_CHOOSE = 'choose';
    public const TYPE_CHOOSE_FROM_TWO = 'choose_from_two';
}
