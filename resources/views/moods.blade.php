@extends('header')

@section('content')
<div class="ml-4">
    <h3>Настроения</h3>
    <button class="btn btn-outline-primary save-moods">Сохранить</button>
    <button class="btn btn-outline-success add-mood">Добавить новый</button>
</div>
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
                    <i class="fa fa-arrows-alt arrows"></i>
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

</script>
<script src="/js/moods.js"></script>
@endsection