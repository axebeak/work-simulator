<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Characters;

class Actions extends Model
{
    protected $guarded = ['id'];
    
    public function getActions(){
        
        return $this->getAll()['actions'];
    }
    
    public function getReactions(){
        
        return $this->getAll()['reactions'];
    }
    
    public function isSelfReaction($reaction){
        if ($reaction['name'] === $reaction['towards']){
            return true;
        }
        
        return false;
    }
    
    public function getAll(){
        $actions =  Characters::select('name', 'actions', 'id')->get();
        $actionList = [];
        $actionList['actions'] = [];
        $actionList['reactions'] = [];
        
        foreach ($actions as $userActions){
            $actionArray = json_decode($userActions->actions);
                foreach ($actionArray as $action){
                    if ($this->where('action_name', $action)->whereNull('towards')->exists()){
                        $results = $this->select('action_name')->where('action_name', $action)->whereNull('towards')->get();
                        foreach($results as $result){
                            array_push($actionList['actions'], ['name' => $result->action_name, 'user_id' => $userActions['id'], 'user' => $userActions->name]);
                        }
                    }
                    if ($this->where('action_name', $action)->whereNotNull('towards')->exists()){
                        $results = $this->select('action_name', 'towards', 'watching', 'consequence')->where('action_name', $action)->whereNotNull('towards')->get();
                        foreach($results as $result){
                            array_push($actionList['reactions'], [
                                'name' => $result->action_name,
                                'user' => $userActions->name,
                                'user_id' => $userActions->id,
                                'towards' => $result->towards,
                                'watching' => json_decode($result->watching),
                                'consequence' => json_decode($result->consequence)
                            ]);
                        }
                    }
                }
        }
        foreach($actionList['actions'] as $key => $list){
            if (empty($list)){
                unset($actionList['actions'][$key]);
            }
        }
        foreach ($actionList['reactions'] as $key => $list){
            if (empty($list)){
                unset($actionList['reactions'][$key]);
            }
        }
        
        return $actionList;
    }
}
