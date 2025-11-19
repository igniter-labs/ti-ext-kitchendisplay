<div class="small overflow-y-auto">
    @if($item->details && $item->details->isNotEmpty())
        @foreach($item->details as $detail)
            <div class="d-flex align-items-center py-1 gap-2">
                <span class="text-secondary fw-medium text-nowrap">{{ $detail->quantity ?? 1 }} x </span>
                <span class="text-body flex-fill text-nowrap">{{ $detail->name ?? lang('igniterlabs.kitchendisplay::default.text_unknown') }}</span>
            </div>
        @endforeach
    @else
        <div class="d-flex align-items-center py-1 text-muted">{{ lang('igniterlabs.kitchendisplay::default.text_no_items') }}</div>
    @endif
</div>
