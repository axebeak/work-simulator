@extends('header')

@section('content')
<div class="container">
    <div class="row mt-5 mb-5 d-flex justify-content-center">
    @foreach ($characters as $character)
        <div class="info-container col ml-2 mr-2" id="{{ $character->id }}">
            <h3 class="text-center">{{ $character->name }}</h3>
            <h5 class="text-center">{{ $character->job_title }}</h5>
            @if (!empty($character->mood))
                @foreach ($moods as $mood)
                
                    @if ($character->mood == $mood->hierarchy)
                        <div class="alert alert-light text-center alert alert-dark" id="mood-{{ $character->id }}">{{ $mood->mood_name }}</div>
                    @endif
                @endforeach
            @endif
            @if (!empty($character->watch_counter))
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

$(document).ready(function() {
  setTimeout(makeAjaxCall, 1000);
});

function actionMessage(name, action){
    return name + ' совершает действие "' + action + '".<br>'
}
function reactionMessage(name, action){
    return name + ' реагирует и совершает действие "'+ action +'".<br>'
}

function successMessage(success){
    return success ? 'Успех!<br>' : 'Провал! <br>' 
}

function paramChangeMessage(param, success, name){
    var parameter = ''
    var result = success ? 'повышается! <br>' : 'понижается... <br>'
    if (param === 'mood'){
        parameter = 'Настроение'
    } else if (param === 'watch_counter'){
        parameter = 'Счетчик слежения'
    } else if (param === 'work'){
        parameter = 'Работа'
    } else {
        parameter = param
    }
    
    return parameter + ' ' + name + ' ' + result 
}

function makeAjaxCall(isAjax = true){
    $.ajax( "/run" )
        .done(function(data) {
            for (var item in data){
                let id = data[item].id
                let name = $('#' + id + ' > h3').text()
                if (!data[item].isReaction){
                    $('#status-' + id).append(actionMessage(name, data[item].action))
                } else {
                    $('#status-' + id).append(reactionMessage(name, data[item].action))
                }
                if (data[item].success){
                    $('#status-' + id).append(successMessage(true))
                } else {
                    $('#status-' + id).append(successMessage(false))
                }
                for (var i in data[item]['params']){
                        $('#status-' + id).append(paramChangeMessage(i, data[item].success, name))
                        if (i === 'mood'){
                            $('#mood-' + id).html('').append(moods[data[item]['params'][i]])
                        }
                        if (i === 'watch_counter'){
                            $('#counter-' + id).html('').append(data[item]['params'].watch_counter)
                        }
                }
            }
            if (!isAjax){
                setTimeout(makeAjaxCall, 10000)
            }
        })
}
 


</script>
@endsection