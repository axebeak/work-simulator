<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Characters;
use App\Actions;
use App\Moods;

class CharacterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $characters = Characters::all();
        $moods = [];
        $actions = [];
        foreach ($characters as $character){
            if (!is_null($character->mood)){
                $moods = $moods + [$character->mood => \App\Moods::select('mood_name')->where(['hierarchy' => $character->mood])->get()->first()->mood_name];
            }
            $actions = $actions + [$character->id => json_decode($character->actions)];
        }
        $actions['all'] = Actions::all('action_name');
        $moods['all'] = Moods::orderBy('hierarchy', 'ASC')->get();
        
        return view('characters')->withCharacters($characters)->withMoods($moods)->withActions($actions);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return redirect('/characters');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:1|max:30|unique:characters',    
            'job_title' => 'required|string|min:1|max:30',
            'mood' => 'integer|max:30',
            'actions' => 'array',
            'actions.*' => 'string'
        ]);
        
        Characters::create([
            'name' => $request->name,
            'job_title' => $request->job_title,
            'mood' => $request->has('mood') ? $request->mood : NULL,
            'actions' => $request->has('actions') ? json_encode($request->actions, JSON_UNESCAPED_UNICODE) : NULL
        ]);

        return redirect('/characters');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
        return redirect('/characters');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        return redirect('/characters');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $request->validate([
            'name' => 'required|string|min:1|max:30',    
            'job_title' => 'required|string|min:1|max:30',
            'mood' => 'integer|max:30',
            'actions' => 'array',
            'actions.*' => 'string'
        ]);
        
        Characters::where('id', $id)->update([
            'name' => $request->name,
            'job_title' => $request->job_title,
            'mood' => $request->has('mood') ? $request->mood : NULL,
            'actions' => $request->has('actions') ? json_encode($request->actions, JSON_UNESCAPED_UNICODE) : NULL
        ]);

        return redirect('/characters');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Characters::where('id', $id)->delete();
        
        return redirect('/characters');
    }
}
