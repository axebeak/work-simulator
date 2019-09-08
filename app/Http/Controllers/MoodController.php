<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Moods;

class MoodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $moods = Moods::orderBy('hierarchy', 'ASC')->get();
        
        return view('moods')->withMoods($moods);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Moods $moods)
    {
       $request->validate([
            'mood' => 'required|string'   
        ]);
        
        $moods->updateMoods($request->mood);
        
        return redirect('/moods');
    }
}
