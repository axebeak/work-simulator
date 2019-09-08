<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Actions;
use App\Watcher;

class Characters extends Model
{
    protected $guarded = ['id'];
    
    public function watcher(){
        
        return $this->hasMany(Watcher::class);
    }
    
    public function runActions(){
        $result = [];
        $act = new Actions;
        foreach ($act->getActions() as $action){
            $result = $result + $this->takeAction($action['user_id'], $action['name']);
        }
        $result = $result + $this->runWatchers();
        
        return $result;
    }
    
    public function runUserActions($name){
        foreach ($this->getActions($name) as $action){
            $this->takeAction($action['user_id'], $action['name']);
        }
        $this->runWatchers();
        
        return true;
    }
    
    public function hasActions($name){
        $act = new Actions;
        $actions = [];
        foreach ($act->getActions() as $action){
            if ($action[is_int($name)? 'user_id' : 'user'] === $name){
                array_push($actions, $action);
            }
        }
        if (empty($actions)){
            return false;
        }
        
        return $actions;
    }
    
    public function hasReactions($name){
        $act = new Actions;
        $actions = [];
        foreach ($act->getReactions() as $action){
            if ($action[is_int($name)? 'user_id' : 'user'] === $name){
                array_push($actions, $action);
            }
        }
        if (empty($actions)){
            return false;
        }

        return $actions;
    }
    
    public function takeAction($id, $action){
        $actionObj = Actions::where('action_name', $action)->first();
        if (is_null($actionObj->towards)){
            return $this->act($id, $actionObj->consequence, $action);
        }
        return $this->react($id, $actionObj->towards, $actionObj->watching, $actionObj->consequence, $action);
    }
    
    public function act($id, $consequence, $action){
        $status = $this->boolAction() ? 'success' : 'fail';
        $consequence = json_decode($consequence);
        
        return $this->applyConsequence($id, $consequence, $status, $action);
    }
    
    public function react($id, $towards, $watching, $consequence, $action){
        $target = $this->where('name', $towards)->first();
        $watching = json_decode($watching);
        $consequence = json_decode($consequence);
        foreach($watching as $trait => $value){
            if ($target->$trait == $value){
                $status = 'success';
            } else {
                $status = 'fail';
            }
            if (empty($consequence->$status)){
                $this->updateWatcher($id, $action);
                return [rand(1,1000000) => ['success' => $status === 'success', 'id' => $id, 'action' => $action, 'isReaction' => true]];
            }
            return $this->applyConsequence($id, $consequence, $status, $action, true);
        }
    }
    
    private function boolAction(){
        if (rand(1,2) === 1){
            return false;
        }
        
        return true;
    }
    
    private function applyConsequence($id, $consequence, $status, $action, $isReaction = false){
        foreach ($consequence->$status as $key => $val){
            $result = $this->select($key)->where('id', $id)->get()->first()->$key;
            $result = $this->validateResult($result, $key, $val, $status, $isReaction);
            $result = [$key => $result];
            $params = array_merge([], $result);
            $this->where('id', $id)->update($result);
            $this->updateWatcher($id, $action);
        }
        $result = [rand(1,1000000) => array_merge(['params' => $params, 'success' => $status === 'success', 'id' => $id, 'action' => $action, 'isReaction' => $isReaction])];
        return $result;
    }
    
    private function validateResult($result, $key, $val, $status, $isReaction = false){
        $status = $status === 'success';
        $available = Meta::select('meta_value')->where('meta_key', $key)->get()->first();
        if (empty($available)){
            throw new \Exception(sprintf('"%s" key either doesn\'t exist or cannot be modified. Please set it along with the available values in the "meta" table of the database.', $key));
        }
        $available = json_decode($available->meta_value);
        if (is_null($result)){
            $result = 0;
        }
        if (!$isReaction){
            $result = $status ? $available[1] : $available[0];
        } else {
            $result = $result + $val;
        }
        if (in_array("*", $available) || in_array($result, $available)){

            return $result;
        }

        return $this->fetchClosestNumber($available, $result);
    }
    
    private function fetchClosestNumber($array, $number) {
        $max = max($array);
        $min = min($array);
        if ($number > $max) {
            return $max;
        }
        if ($number < $min){
            return $min;
        }

        return $number;
    }
    
    public function setUpWatchers($user){
        $reactions = $this->hasReactions($user);
        if (!$reactions){
            return false;
        }
        foreach ($reactions as $reaction){
            if (Watcher::where('action_name', $reaction['name'])->where('characters_id', $reaction['user_id'])->exists()){
                continue;
            }
            Watcher::create([
                'characters_id' => $reaction['user_id'],
                'action_name' => $reaction['name'],
                'last_updated' => NULL
            ]);
        }
        
        return true;
    }
    
    public function runWatchers(){
        $result = [];
        foreach ($this->checkWatchers() as $id => $array){
            foreach ($array as $action => $val){
                if ($val){
                    continue;
                }
                $result = $result + $this->takeAction($id, $action);
            }
        }
        if (!$this->watchersAlreadyRun()){
            $result = $result + $this->runWatchers();
        }
        
        return $result;
    }
    
    public function watchersAlreadyRun(){
        foreach ($this->checkWatchers() as $id){
            foreach ($id as $action){
                if (!$action){
                    return false;
                }
            }
        }
        
        return true;
    }
    
    public function checkWatchers(){
        $actions = new Actions;
        $watchers = [];
        foreach ($actions->getReactions() as $reaction){
            $watchers = $watchers + [$reaction['user_id'] => $this->checkWatcher($reaction['user_id'])];
        }
        
        return $watchers;
    }
    
    public function checkWatcher($id){
        $reactions = $this->hasReactions($id);
        $results = [];
        if (!$reactions){
            return false;
        }
        foreach ($reactions as $reaction){
            if (!Watcher::where('action_name', $reaction['name'])->where('characters_id', $id)->exists()){
                $this->setUpWatchers($id);
            }
            $watcherTime = Watcher::select('last_updated')->where('action_name', $reaction['name'])->where('characters_id', $id)->first()->last_updated;
            $watchedTime = $this->select('updated_at')->where('name', $reaction['towards'])->first()->updated_at;
            if ($watcherTime == $watchedTime){
                $results = $results + [$reaction['name'] => true];
                continue;
            }
            $results = $results + [$reaction['name'] => false];
        }
        
        return $results;
    }
    
    public function updateWatcher($id, $action){
        if (!Watcher::where('action_name', $action)->where('characters_id', $id)->exists()){
            $this->setUpWatchers($id);
        }
        $towards = Actions::select('towards')->where('action_name', $action)->first()->towards;
        if (is_null($towards)){
            return true;
        }
        $watchedTime = $this->select('updated_at')->where('name', $towards)->first()->updated_at;
        Watcher::where('action_name', $action)->where('characters_id', $id)->update([
            'last_updated' => $watchedTime     
        ]);
        
        return true;
    }
    
}
