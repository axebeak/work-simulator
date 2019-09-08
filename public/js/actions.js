
$('.button-new').click(
    function(){
      $('.main-cont').prepend(renderFull())  
});

$(document).on('click', '.delete-form-tmp', 
    function(e){
        e.preventDefault();
        let checkId = $(this).attr('id').replace(/delete-form-tmp-/g,'');
        $('#info-container-' + checkId).remove();
})

$(document).on('change','.is_reaction',
    function(){
        let checkId = $(this).attr('id').replace(/is_reaction-/g,'');
        if ($(this).is(':checked')) {
            addReaction(checkId, true)
        } else {
            $('#reactions-' + checkId).html("Действие не является реакцией").addClass('no-reactions');
        }
});

$(document).on('click','.add-success',
    function(){
        let checkId = $(this).attr('id').replace(/add-success-/g,'');
        addTrigger(checkId, true)
});

$(document).on('click','.add-fail',
    function(){
        let checkId = $(this).attr('id').replace(/add-fail-/g,'');
        addTrigger(checkId, false)
});


$(document).on('click','.add-reaction',
    function(){
        let checkId = $(this).attr('id').replace(/add-reaction-/g,'');
        addReaction(checkId)
});

var success = renderTrigger()

var fail = renderTrigger(false)

function addReaction(id, withCharacters = false){
    if ($('#reactions-' + id).hasClass('no-reactions')){
        $('#reactions-' + id).removeClass('no-reactions').empty();
    }
    $('#reactions-' + id).append(withCharacters ? renderReaction(id, true) : renderReaction(id))
    
    return true
}

function addTrigger(id, isSuccess){
    let el = isSuccess ? 'success-' : 'fail-'
    return $('#' + el + id).append(isSuccess ? success : fail)
}

function renderCharacters(){
    var charactersSelect = '<select name="towards">'
    for (var i = 0; i < characters.length; i++) {
        charactersSelect = charactersSelect + '<option value="' + characters[i] + '">' +  characters[i] + "</option>"
    }
    charactersSelect = charactersSelect + '</select>'
    
    return charactersSelect
}

function renderMeta(name){
    var metaSelect = '<select name="' + name + '">'
    for (var prop in meta){
        metaSelect = metaSelect + '<option value="' + prop + '">' + prop + '</option>'
    }
    metaSelect = metaSelect + '</select>'
    
    return metaSelect
}

function renderMetaOptions(option = false){
    if (!option || typeof meta[option] === "undefined"){
        metaValues = meta[Object.keys(meta)[0]]
    } else {
        metaValues = meta[option]
    }
    var metaOptions = '<select name="' + name + '">'
    for (var i = 0; i < metaValues.length; i++){
        metaOptions = metaOptions + '<option value="' + metaValues[i] + '">' + metaValues[i] + '</option>'
    }
    metaOptions = metaOptions + '</select>'
    
    return metaOptions
}

function renderReaction(id, withCharacters = false){
    let charList = renderCharacters()
    let metaList = renderMeta('watching_param[]')
    let metaOptionsList = renderMetaOptions()
    var reaction = withCharacters ? `
        <div>Направлено на:</div>
        ${charList}
        <div>Условия</div>
        <div class="reaction mb-2 mt-2">
            ${metaList}
            <input type="text" class="input-num" name="watching_value[]" value="1">
            <i class="fa fa-times" aria-hidden="true"></i>
            </div>
    </div>
    <a id="add-reaction-${id}" class="add-reaction" href="#">Добавить условие</a>
    ` : `
        <div class="reaction mb-2 mt-2">
            ${metaList}
            <input type="text" class="input-num" name="watching_value[]" value="1">
            <i class="fa fa-times" aria-hidden="true"></i>
        </div>
    `
    
    return reaction
}

function renderTrigger(isSuccess = true){
    if (isSuccess){
        var param = 'success'
    } else {
        var param = 'fail'
    }
    let metaList = renderMeta(param + '_param[]')
    var trigger = `
        <div class="${param}-fields mt-2 mb-2">
            ${metaList}
            <input type="text" class="input-num" name="${param}_value[]" value="1">
            <i class="fa fa-times" aria-hidden="true"></i>
        </div>
    `
    
    return trigger
}

function renderFull(){
    var id = Math.floor(Math.random() * Math.floor(100000))
    var success = renderTrigger()
    var fail = renderTrigger(false)
    var form = `
        <div id="info-container-${id}" class="col info-container ml-4 mr-4 p-4 text-center">
            <form method="POST" action="/actions"  class="h-100">
                    <div id="box-main-${id}" class="box-main">
                        <div>Название Действия:</div>
                        <input type="text" placeholder="Название" name="action_name" > 
                        <div>Реакция?
                            <input type="checkbox" class="is_reaction" id="is_reaction-${id}">
                        </div>
                    </div>
                    <div id="reactions-${id}" class="no-reactions reactions">Действие не является реакцией</div>
                    <div class="result" id="result-${id}">
                        <div>Результат</div>
                        <div>Успех:</div>
                        <div class="success" id="success-${id}">
                            ${success}
                        </div>
                        <div><a class="add-success" id="add-success-${id}" href="#">Добавить успех</a></div>
                        <div>Провал:</div>
                        <div class="fail" id="fail-${id}">
                            ${fail}
                        </div>
                        <div><a href="#" class="add-fail" id="add-fail-${id}">Добавить провал</a></div>
                        <button type="submit" class="btn btn-primary">Сохранить</button>
                        <button type="submit" id="delete-form-tmp-${id}" class="btn btn-primary delete-form-tmp">Удалить</button>
                    </div>
            </form>
        </div>
    `
    return form
}