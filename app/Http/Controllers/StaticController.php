<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StaticController extends controller
{
    public function getHome()
    {
        $sexy_words = [
            'sexy', 'racy', 'inviting', 'mature', 'provocative', 'seductive', 'sensual', 'arousing', 'kissable',
            'voluptuous', 'titillating', 'steamy', 'suggestive', 'spicy', 'flirtatious'
        ];

        shuffle($sexy_words);

        $sexy_word = $sexy_words[0];

        return view('home', ['sexy_word' => $sexy_word]);
    }
}