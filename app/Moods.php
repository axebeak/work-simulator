<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Moods extends Model
{
    
    protected $guarded = ['id'];
    
    public function updateMoods($moods){
        $moods = json_decode($moods);
        if ($moods === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('You must pass valid JSON when submitting new moods.');   
        }
        $heirarchies = [];
        foreach ($moods as $id => $mood){
            array_push($heirarchies, $mood->heirarchy);
            if ($this->where('id', '=', $id)->exists()){
                $dbMood = $this->find($id);
                $dbMood->mood_name = $mood->name;
                $dbMood->hierarchy = $mood->heirarchy;
                
                $dbMood->save();
                continue;
            }
            $this->create([
                'mood_name' => $mood->name,
                'hierarchy' => $mood->heirarchy
            ]);
        }
        Meta::where('meta_key', 'mood')->update(['meta_value' => json_encode($heirarchies)]);
        
        return true;
    }
    
}
