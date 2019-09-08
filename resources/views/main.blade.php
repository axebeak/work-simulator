@extends('header')

@section('content')
<div class="container">
    <div class="row mt-3">
        <div class="col d-flex justify-content-center">
            <button class="btn btn-outline-success take-action">Совершить действие</button>
        </div>
        <div class="col d-flex justify-content-center">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="automatic" checked>
                <label for="automatic" class="custom-control-label">Совершать действия автоматически</label>
            </div>
        </div>
    </div>
    <div class="row mt-5 mb-5 d-flex justify-content-center">
    @foreach ($characters as $character)
        <div class="info-container col ml-2 mr-2" id="{{ $character->id }}">
            <h3 class="text-center">{{ $character->name }}</h3>
            <h5 class="text-center">{{ $character->job_title }}</h5>
            @if (is_numeric($character->work))
                <div class="alert alert-light text-center alert alert-dark" >Состояние Работы: <span id="work-{{ $character->id }}">{{ $character->work }}</span></div>
            @endif
            @if (!empty($character->mood))
                @foreach ($moods as $mood)
                
                    @if ($character->mood == $mood->hierarchy)
                        <div class="alert alert-light text-center alert alert-dark" id="mood-{{ $character->id }}">{{ $mood->mood_name }}</div>
                    @endif
                @endforeach
            @endif
            @if (!empty($character->watch_counter) || is_numeric($character->watch_counter))
            <div class="counter-container alert alert-dark text-center">
                <div>Счетчик слежения: <span class="counter" id="counter-{{ $character->id }}">{{ $character->watch_counter }}</span>
                </div>
            </div>
            @endif
            <div class="status mb-3 mt-5">
                <h6>Статус:</h6>
                <div class="status-log" id="status-{{ $character->id }}">
                </div>
            </div>
        </div>
    @endforeach
        
    </div>
</div>
<script>

var moods = {}

@foreach ($moods as $mood)
    moods['{{ $mood->id }}'] = '{{ $mood->mood_name }}'
@endforeach

</script>

<script src="/js/main.js"></script>
@endsection