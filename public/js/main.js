$(document).ready(function() {
    setTimeout(makeAjaxCall, 1000);
});

$('.take-action').click(function(){
    makeAjaxCall(false)
})

$('#automatic').click(function(){
    if ($('#automatic').prop("checked")){
        setTimeout(makeAjaxCall, 1000);
    }
})

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
                        if (i === 'work'){
                            $('#work-' + id ).html('').append(data[item]['params'].work)
                        }
                        if (i === 'mood'){
                            $('#mood-' + id).html('').append(moods[data[item]['params'][i]])
                        }
                        if (i === 'watch_counter'){
                            $('#counter-' + id).html('').append(data[item]['params'].watch_counter)
                        }
                }
            }
            if (isAjax && $('#automatic').prop("checked")){
                setTimeout(makeAjaxCall, 10000)
            }
        })
}
 
