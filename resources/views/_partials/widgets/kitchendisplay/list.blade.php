<div data-control="kitchen-display-list" class="overflow-y-auto" style="height: calc(100vh - 230px);">
    @if(empty($boardItems))
        <div class="text-center text-muted py-5">
            <i class="fa fa-inbox fa-3x mb-3 opacity-50"></i>
            <p class="mb-0">{{ lang('igniterlabs.kitchendisplay::default.text_empty') }}</p>
        </div>
    @else
        <div class="row g-3">
            @foreach($boardItems as $item)
                <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                    {!! $this->makePartial('kitchendisplay/card', ['item' => $item]) !!}
                    {!! $this->makePartial('kitchendisplay/custom-time-modal', ['item' => $item]) !!}
                    {!! $this->makePartial('kitchendisplay/card-details-modal', ['item' => $item]) !!}
                </div>
            @endforeach
        </div>
    @endif
</div>

