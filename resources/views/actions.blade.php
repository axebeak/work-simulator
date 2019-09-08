@extends('header')

@section('title', 'Действия')

@section('content')
    <div class="header row">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="col" style="display:none;"> 
            <div>Значения, которые могут принимать параметры: </div>
            @foreach ($meta as $mt)
                <div>{{ $mt->meta_key }}: {!! rtrim(implode(', ', $mt->meta_value), ',') !!}</div>
            @endforeach
        </div>
        <button class="btn ml-4 mt-2 btn-outline-success button-new" id="new-btn">Создать новое</button>
    </div>
    <div class="row flex-nowrap main-cont overflow-auto mt-4">
    @foreach ($actions as $action)
        <div id="info-container-{{ $action->id }}" class="col info-container ml-4 mr-4 p-4 text-center">
            <form method="POST" action="/actions/{{ $action->id }}" class="h-100">
            @csrf
            @method('PATCH')
                <div id="box-main-{{ $action->id }}" class="box-main">
                    <div>Название Действия:</div>
                    <input type="text" placeholder="Название" class="input-name" name="action_name" value="{{ $action->action_name }}"> 
                    <div>Реакция?  <input type="checkbox" class="is_reaction" id="is_reaction-{{ $action->id }}" {{ !empty($action->towards) ? 'checked' : '' }}></div>
                </div>
                @if (!empty($action->towards))
                    <div id="reactions-{{ $action->id }}" class="reactions">
                        <div>Направлено на:</div>
                        <select name="towards">
                            <option value="{{ $action->towards }}">{{ $action->towards }}</option>
                            @foreach ($characters as $character)
                                @if ($character->name === $action->towards)
                                    @continue
                                @endif
                                <option value="{{ $character->name }}">{{ $character->name }}</option>
                            @endforeach
                        </select>
                        <div>Условия</div>
                        <div class="reaction mb-2 mt-2">
                            @foreach ($action->watching as $key => $value)
                                <select name="watching_param[]">
                                    <option value="{{ $key }}">{{ $key }}</option>
                                    @foreach ($meta as $mt)
                                        @if ($mt->meta_key === $key)
                                            @continue
                                        @endif
                                        <option value="{{ $mt->meta_key }}">{{ $mt->meta_key }}</option>
                                    @endforeach
                                </select>
                                <input type="text" class="input-num"  name="watching_value[]" value="{{ $value }}">
                                <i class="fa fa-times" aria-hidden="true"></i>
                            @endforeach
                        </div>
                    </div>
                        <div><a id="add-reaction-{{ $action->id }}" class="add-reaction" href="#">Добавить условие</a></div>
                @else
                    <div id="reactions-{{ $action->id }}" class="no-reactions reactions">Действие не является реакцией</div>
                @endif
                <div id="result-{{ $action->id }}" class="result">
                    <div>Результат</div>
                    <div>Успех:</div>
                    <div id="success-{{ $action->id }}" class="success">
                        @if (!empty($action->consequence->success))
                            @foreach ($action->consequence->success as $param => $value)
                                <div class="success-fields mt-2 mb-2">
                                    <select name="success_param[]">
                                        <option value="{{ $param }}">{{ $param }}</option>
                                        @foreach ($meta as $mt)
                                            @if ($mt->meta_key === $param)
                                                @continue
                                            @endif
                                            <option value="{{ $mt->meta_key }}">{{ $mt->meta_key }}</option>
                                        @endforeach
                                    </select>
                                    <input type="text" class="input-num" name="success_value[]" value="{{ $value }}">
                                    <i class="fa fa-times" aria-hidden="true"></i>
                                </div>
                            @endforeach
                        @else
                            <div>Успех не установлен</div>
                        @endif
                    </div>
                    <div><a id="add-success-{{ $action->id }}" class="add-success" href="#">Добавить успех</a></div>
                    <div id="fail-{{ $action->id }}" class="fail">
                        <div>Провал:</div>
                        @if (!empty($action->consequence->fail))
                            @foreach ($action->consequence->fail as $param => $value)
                            <div class="fail-fields mt-2 mb-2">
                                <select name="fail_param[]">
                                    <option value="{{ $param }}">{{ $param }}</option>
                                    @foreach ($meta as $mt)
                                        @if ($mt->meta_key === $param)
                                            @continue
                                        @endif
                                        <option value="{{ $mt->meta_key }}">{{ $mt->meta_key }}</option>
                                    @endforeach
                                </select>
                                <input type="text" class="input-num" name="fail_value[]" value="{{ $value ? $value : 0 }}">
                                <i class="fa fa-times" aria-hidden="true"></i>
                            </div>
                            @endforeach
                        @else
                            <div>Провал не установлен</div>
                        @endif
                    </div>
                    <div><a id="add-fail-{{ $action->id }}" class="add-fail" href="#">Добавить провал</a></div>
                </div>
                <div class="button-box">
                    <button type="submit" class="btn btn-outline-primary">Сохранить</button>
                <span>
                    <form method="POST" action="/actions/{{ $action->id }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-primary">Удалить</button>
                    </form>
                </span>
                </div>
            </form>
        </div>
    @endforeach
    </div>

<script>
var characters = []
var meta = {}

@foreach ($characters as $character)
    characters.push('{{ $character->name }}')
@endforeach

@foreach ($meta as $mt)
    meta['{{ $mt->meta_key }}'] = @json($mt->meta_value)

@endforeach
  
</script>
<script src="js/actions.js"></script> 

@endsection