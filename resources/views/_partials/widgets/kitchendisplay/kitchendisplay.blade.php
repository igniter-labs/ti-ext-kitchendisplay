<div
    data-control="kitchen-display"
    data-view-mode="{{$viewMode}}"
    class="p-3"
>
    @if($viewMode === 'list')
        {!! $this->makePartial('kitchendisplay/list') !!}
    @else
        {!! $this->makePartial('kitchendisplay/board') !!}
    @endif
</div>
