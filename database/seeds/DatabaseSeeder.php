<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->populateCharacters();
        $this->populateActions();
        $this->populateMoods();
        $this->populateMeta();
        
        return true;
    }
    
    public function populateCharacters(){
        $characters = [
            'Юный Программист' => [
                'job_title' => 'Программист',
                'mood' => NULL,
                'work' => NULL,
                'watch_counter' => NULL,
                'actions' => json_encode(['Работа'], JSON_UNESCAPED_UNICODE)
            ],
            'T-70' => [
                'job_title' => 'Тимлид',
                'mood' => 2,
                'work' => NULL,
                'watch_counter' => NULL,
                'actions' => json_encode(['Оценка'], JSON_UNESCAPED_UNICODE)
            ],
            'T-1000' => [
                'job_title' => 'HR',
                'mood' => NULL,
                'work' => NULL,
                'watch_counter' => 0,
                'actions' => json_encode(['Слежение за выговорами'], JSON_UNESCAPED_UNICODE)
            ],
            'T-1001' => [
                'job_title' => 'Менеджер',
                'mood' => NULL,
                'work' => NULL,
                'watch_counter' => 0,
                'actions' => json_encode(['Слежение за похвалой'], JSON_UNESCAPED_UNICODE)
            ]
        ];
        
        foreach ($characters as $character => $array){
            DB::table('characters')->insert([
                'name' => $character,
                'job_title' => $array['job_title'],
                'mood' =>  $array['mood'],
                'work' => $array['work'],
                'watch_counter' => $array['watch_counter'],
                'actions' => $array['actions']
            ]);
        }
        
        return true;
    }
    
    public function populateMoods(){
        $moods = [
            'Хорошее настроение' => 1,
            'Нормальное настроение' => 2,
            'Плохое Настроение' => 3,
            'Настроение "не попадись на глаза"' => 4,
        ];
        
        foreach($moods as $mood => $place){
            DB::table('moods')->insert([
                'hierarchy' => $place,
                'mood_name' => $mood
            ]);
        }
        return true;
    }
    
    public function populateActions(){
        $actions = [
            'Работа' => [
                'towards' => NULL,
                'watching' => NULL,
                'consequence' => json_encode([
                    'success' => ['work' => true],
                    'fail' => ['work' => false]
                ])
            ],
            'Оценка' => [
                'towards' => 'Юный Программист',
                'watching' => json_encode(['work' => true]),
                'consequence' => json_encode([
                    'success' => ['mood' => -1],
                    'fail'=> ['mood' => 1]
                ])
            ],
            'Слежение за выговорами' => [
                'towards' => 'T-70',
                'watching' => json_encode(['mood' => 1]),
                'consequence' => json_encode([
                    'success' => ['watch_counter' => 1],
                    'fail'=> false
                ])
            ],
            'Слежение за похвалой' => [
                'towards' => 'T-70',
                'watching' => json_encode(['mood' => 4]),
                'consequence' => json_encode([
                    'success' => ['watch_counter' => 1],
                    'fail'=> false
                ])
            ]
        ];
        
        foreach($actions as $action => $array){
            DB::table('actions')->insert([
                'action_name' => $action,
                'towards' => $array['towards'],
                'watching' => $array['watching'],
                'consequence' => $array['consequence']
            ]);
        }
        
        return true;
    }
    
    public function populateMeta(){
        $meta = [
            'work' => json_encode([0, 1]),
            'mood' => json_encode([1, 2, 3, 4]),
            'watch_counter' => json_encode(['*'])
        ];
        
        foreach($meta as $key => $value){
            DB::table('meta')->insert([
                'meta_key' => $key,
                'meta_value' => $value
            ]);
        }
    }
    
}
