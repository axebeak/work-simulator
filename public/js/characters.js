$(document).on('click','.add-action',
    function(){
        let checkId = $(this).attr('id').replace(/add-action-/g,'');
        addAction(checkId)
});

$(document).on('click','.add-new-char',
    function(){
        $('.main-cont').prepend(renderFull())
});

$(document).on('click','.delete-new',
    function(){
        $(this).parent().parent().parent().remove()
});

$(document).on('change','.has_mood',
    function(){
        let checkId = $(this).attr('id').replace(/has_mood-/g,'');
        if ($(this).is(':checked')) {
            $('#mood-' + checkId).html(renderMoods())
        } else {
            $('#mood-' + checkId).html("Персонаж не имеет настроения");
        }
});

$(document).on('click','.fa-times',
    function(){
        $(this).parent().remove();
});

function addAction(id){
    $('#actions-container-' + id).append(renderActions())
    
    return true
}

function renderMoods(){
    var moodSelect = `
    <div>
        <select name="mood">`
    for (var mood in moods){
        moodSelect = moodSelect + '<option value="' + mood + '">' + moods[mood] + '</option>'
    }
    moodSelect = moodSelect + `
        </select>
    </div>
    `
    
    return moodSelect
}

function renderActions(){
    var actionSelect = `
        <div class="action-item mt-2 mb-2">
            <select name="actions[]">
    `
    for (var i = 0; i < actions.length; i++){
        actionSelect = actionSelect + '<option value="' + actions[i] + '">' + actions[i] + '</option>'
    }
    actionSelect = actionSelect + '</select> <i class="fa fa-times" aria-hidden="true"></i></div>'
    
    return actionSelect
}

function renderFull(){
    var id = Math.floor(Math.random() * Math.floor(100000))
    var action = renderActions()
    var fullForm = `
        <div class="col info-container ml-4 mr-4 p-4 text-center">
            <form id="character-${id}" method="POST" action="/characters" class="h-100">
                <h3>Имя:</h3>
                <input type="text" placeholder="Имя" class="input-name" name="name" value=""> 
                <div class="mt-1 mb-1">
                    <div>Должность:</div> 
                    <input type="text" placeholder="Имя" class="input-name" name="job_title" value=""> 
                </div>
                <div>Настроение <input type="checkbox" class="has_mood" id="has_mood-${id}"></div>
                <div class="mood mt-1 mb-1" id="mood-${id}">
                        Персонаж не имеет настроения
                </div>
                <div class="mt-1 mb-1">
                    <div>Счетчик слежения:</div> 
                    <div>Персонаж еще не наблюдал за чужим состоянием.</div>
                </div>
                <div>Выполняет действия:</div>
                <div id="actions-container-${id}" class="actions-container">
                    ${action}
                </div>
                <a href="#" id="add-action-${id}" class="add-action">Добавить действие</a>
            </form>
            <div class="button-box d-flex">
                <button form ="character-${id}" type="submit" class="btn btn-primary mr-2">Сохранить</button>
                <span class="ml-2">
                    <button type="submit" class="btn btn-primary delete-new">Удалить</button>
                </span>
            </div>
        </div>
    `
    
    return fullForm
}