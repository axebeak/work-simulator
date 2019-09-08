<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Actions;

class ActionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $actions = \App\Actions::all();
        $meta = \App\Meta::all();
        $moods = \App\Moods::all();
        $characters = \App\Characters::all();
        
        foreach ($actions as $action){
            $action->consequence = json_decode($action->consequence);
            if (is_null($action->watching)){
                continue;
            }
            $action->watching = json_decode($action->watching);
        }
        foreach ($meta as $mt){
            $mt->meta_value = json_decode($mt->meta_value);
        }

        return view('actions')->withActions($actions)->withMoods($moods)->withMeta($meta)->withCharacters($characters);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        return redirect('/actions');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Actions $actions, Request $request)
    {
        
        $request->validate([
            'action_name' => 'required|max:255|unique:actions',
            'towards' => 'exists:characters,name|string',
            'watching_param' => 'array',
            'watching_param.*' => 'exists:meta,meta_key|string',
            'watching_value' => 'array',
            'watching_value.*' => 'numeric|max:128',
            'success_param' => 'array',
            'success_param.*' => 'exists:meta,meta_key|string',
            'success_value' => 'array',
            'success_value.*' => 'numeric|max:128',
            'fail_param' => 'array',
            'fail_param.*' => 'exists:meta,meta_key|string',
            'fail_value' => 'array',
            'fail_value.*' => 'numeric|max:128'
        ]);
        
        $validated = $this->validateRequestParams($request);
        
        $actions->action_name = $request->action_name;
        $actions->towards = $request->has('watching_param') && $request->has('towards') ? $request->towards : NULL;
        $actions->watching = $request->has('watching_param') && $request->has('towards') ? $validated['watcher'] : NULL;
        $actions->consequence = json_encode(['success' => $validated['success'], 'fail' => $validated['fail']]);
        $actions->save();
        
        return redirect('/actions');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        return redirect('/actions');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
        return redirect('/actions');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Actions $actions, Request $request, $id)
    {
        $request->validate([
            'action_name' => 'required|max:255',
            'towards' => 'exists:characters,name|string',
            'watching_param' => 'array',
            'watching_param.*' => 'exists:meta,meta_key|string',
            'watching_value' => 'array',
            'watching_value.*' => 'numeric|max:128',
            'success_param' => 'array',
            'success_param.*' => 'exists:meta,meta_key|string',
            'success_value' => 'array',
            'success_value.*' => 'numeric|max:128',
            'fail_param' => 'array',
            'fail_param.*' => 'exists:meta,meta_key|string',
            'fail_value' => 'array',
            'fail_value.*' => 'numeric|max:128'
        ]);
        
        $validated = $this->validateRequestParams($request);
        
        $action = $actions->find($id);
        $action->action_name = $request->action_name;
        $action->towards = $request->has('watching_param') && $request->has('towards') ? $request->towards : NULL;
        $action->watching = $request->has('watching_param') && $request->has('towards') ? $validated['watcher'] : NULL;
        $action->consequence = json_encode(['success' => $validated['success'], 'fail' => $validated['fail']]);
        $action->save();
        
        return redirect('/actions');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Actions $actions, $id)
    {
        $actions->destroy($id);
        
        return redirect('/actions');
    }
    
    private function validateRequestParams($request){
        if ($request->has('success_param') && $request->has('success_value')) {
            $success = $this->constructArrays($request->success_param, $request->success_value);
        } else {
            $success = false;
        }
        if ($request->has('fail_param') && $request->has('fail_value')){
            $fail = $this->constructArrays($request->fail_param, $request->fail_value);
        } else {
            $fail = false;
        }
        if ($request->has('watching_param') && $request->has('watching_value')){
            $watcher = json_encode($this->constructArrays($request->watching_param, $request->watching_value));
        } else {
            $watcher = NULL;
        }
        
        return [
            'success' => $success,
            'fail' => $fail,
            'watcher' => $watcher
        ];
    }    
    
    private function constructArrays($params, $values){
        if (is_null($params) || is_null($values)){
            return NULL;
        }
        if (count($params) !== count($values)){
            return false;
        }
        $array = [];
        for ($i = 0; $i <= count($params) - 1; $i++){
            $array = $array + [$params[$i] => $values[$i]];
        }
        
        return $array;
    }
}
