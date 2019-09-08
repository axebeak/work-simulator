@extends('header')

@section('content')
<h3>Настроения</h3>
<button class="btn btn-outline-primary save-moods">Сохранить</button>
<button class="btn btn-outline-success add-mood">Добавить новый</button>
<span>
    <form id="mood-form" method="POST" action="/moods">
        @csrf
        <input type="hidden" class="mood-input" name="mood" value="">
    </form>
</span>
<div class="container row">
    <div class="col">
        <ul id="sortable" class="moods">
            @foreach ($moods as $mood)
                <li class="ui-state-default mb-2 mt-2 mood-li" id="{{ $mood->id }}">
                    <span class="heirarchy-val">{{ $mood->hierarchy }}</span>
                    <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
                    <span class="mood-val ml-2">{{ $mood->mood_name }}</span>
                    <i class="fa fa-times delete-mood-li" aria-hidden="true"></i>
                </li>
            @endforeach
        </ul>
    </div>
</div>

<script>
  
var moodIds = []

@foreach ($moods as $mood)
    moodIds.push('{{ $mood->id }}')
@endforeach

$(document).mouseup(function() {
    updateValues()
});

$(document).on('click','.save-moods',
    function(){
        var moodData = {}
        for (var id in moodIds){
            moodData[moodIds[id]] = {}
            moodData[moodIds[id]]['heirarchy'] = $("#" + moodIds[id] + " > .heirarchy-val").text()
            moodData[moodIds[id]]['name'] = $("#" + moodIds[id] + " > .mood-val").text()
        }
        var result = JSON.stringify(moodData)
        $('.mood-input').val(result)
        $("#mood-form").submit();
});

$(document).on('click','.fa-times',
    function(){
        let id = $(this).parent().attr("id")
        var index = moodIds.indexOf(id)
        if (index !== -1) moodIds.splice(index, 1)
        updateValues()
});

$(document).on('click','.add-mood',
    function(){
        $('.moods').append(renderMood())
        updateValues()
        inializeInputs()
});

$( function() {
    $( "#sortable" ).sortable()
    $( "#sortable" ).disableSelection()
    inializeInputs()
} );


function updateValues(){
    var positions = moodPositions()
    for (var id in positions){
        $("#" + id + "> .heirarchy-val").html(positions[id])
    }
}

function moodPositions(){
    var positions = {}
    for (var id in moodIds){
        if(!$("#"+ moodIds[id]).length){
            delete moodIds[id]; 
        }
        let pos = $("#" + moodIds[id]).position().top
        positions[moodIds[id]] = pos 
    }
    var sorted = Object.values(positions).sort((a, b) => a - b)
    var places = {}
    for (var position in positions){
        places[position] = sorted.findIndex(x => x === positions[position]) + 1;
    }

    return places
}

function renderMood(){
    var id = Math.floor(Math.random() * Math.floor(100000))
    var positions = moodPositions()
    var position = Object.keys(positions).length + 1;
    var mood = `
        <li class="ui-state-default mb-2 mt-2 mood-li" id="${id}">
            <span class="heirarchy-val">${position}</span>
            <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
            <span class="mood-val ml-2">Без Названия</span>
            <i class="fa fa-times delete-mood-li" aria-hidden="true"></i>
        </li>
    `
    moodIds.push(id)
    
    return mood
}

function inializeInputs(){
    $(".mood-val").each(function () {
        var label = $(this);
        
        var labelid = label.parent().attr("id")
 
        label.after("<input type = 'text' style = 'display:none' />");
        
        var textbox = $(this).next();
 
        textbox[0].name = this.id.replace("lbl", "txt");
 
        textbox.val(label.html());
 
        label.click(function () {
            $(this).hide();
            $(this).next().show();
            $('#' + labelid +' > .delete-mood-li').css({'left': '80px'})
        });
 
        textbox.focusout(function () {
            $(this).hide();
            $(this).prev().html($(this).val());
            $(this).prev().show();
            $('#' + labelid +' > .delete-mood-li').css({'left': '325px'})
        });
    });
}

</script>
@endsection