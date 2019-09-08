@extends('header')

@section('title', 'Characters')

@section('content')
    <header class="row">
        <button class="btn btn-outline-primary add-new-char mt-3 ml-4">Создать нового</button>
    </header>
    <div class="row flex-nowrap main-cont overflow-auto mt-4">
    @foreach ($characters as $character)
        <div class="col info-container ml-4 mr-4 p-4 text-center">
            <form id="character-{{ $character->id }}" method="POST" action="/characters/{{ $character->id }}" class="h-100">
                @csrf
                @method('PATCH')
                <h3>Имя:</h3>
                <input type="text" placeholder="Имя" class="input-name" name="name" value="{{ $character->name }}"> 
                <div class="mt-1 mb-1">
                    <div>Должность:</div> 
                    <input type="text" placeholder="Имя" class="input-name" name="job_title" value="{{ $character->job_title }}"> 
                </div>
                <div>Настроение <input type="checkbox" class="has_mood" id="has_mood-{{ $character->id }}" {{ !empty($character->mood) ? 'checked' : '' }}></div>
                <div class="mood mt-1 mb-1" id="mood-{{ $character->id}}">
                    @if (!empty($character->mood))
                        <select name="mood">
                            @foreach ($moods['all'] as $mood)
                                @if ($moods[$character->mood] == $mood->mood_name)
                                    <option value="{{ $character->mood }}" selected>{{ $moods[$character->mood] }}</option>
                                    @continue
                                @endif
                                <option value="{{ $mood->hierarchy }}">{{ $mood->mood_name }}</option>
                            @endforeach
                        </select>
                    @else
                        Персонаж не имеет настроения
                    @endif
                </div>
                <div class="mt-1 mb-1">
                    <div>Счетчик слежения:</div> 
                    <div>{{ !empty($character->watch_counter) ? $character->watch_counter : 'Персонаж еще не наблюдал за чужим состоянием.'  }}</div>
                </div>
                <div>Выполняет действия:</div>
                
                <div id="actions-container-{{ $character->id }}" class="actions-container">
                    @foreach ($actions[$character->id] as $charAction)
                        <div class="action-item mt-2 mb-2">
                            <select name="actions[]">
                                <option value="{{ $charAction }}" selected>{{ $charAction }}</option>
                                @foreach ($actions['all'] as $action)
                                    @if ($charAction === $action->action_name)
                                        @continue
                                    @endif
                                    <option value="{{ $action->action_name }}">{{ $action->action_name }}</option>
                                @endforeach
                            </select>
                        <i class="fa fa-times" aria-hidden="true"></i>
                        </div>
                    @endforeach
                </div>
                <a href="#" id="add-action-{{ $character->id }}" class="add-action">Добавить действие</a>
                    
            </form>
            <div class="button-box bb-chars d-flex ml-2">
                <button form ="character-{{ $character->id }}" type="submit" class="btn btn-outline-success mr-2">Сохранить</button>
                <span class="ml-2">
                    <form method="POST" action="/characters/{{ $character->id }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger">Удалить</button>
                    </form>
                </span>
            </div>
        </div>
    @endforeach
    </div>
<script>

var moods = {}

var actions = []

@foreach ($actions['all'] as $action)
    actions.push('{{ $action->action_name }}')
@endforeach

@foreach ($moods['all'] as $mood)
    moods['{{ $mood->hierarchy }}'] = '{{ $mood->mood_name }}'
@endforeach

</script>

<script src="js/characters.js"></script>
@endsection