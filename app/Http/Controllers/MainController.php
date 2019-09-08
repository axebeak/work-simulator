<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Characters;
use App\Moods;

class MainController extends Controller
{
    public function index(Characters $char, Moods $mood){
        
        $characters = $char->all();
        $moods = $mood->all();
        
        return view('main')->withCharacters($characters)->withMoods($moods);
    }
    
    public function act(Characters $characters){
        
        return response()->json($characters->runActions());
    }
}
