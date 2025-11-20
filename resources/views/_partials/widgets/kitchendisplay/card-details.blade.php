<div class="small overflow-y-auto">
    @if($item->details && $item->details->isNotEmpty())
        @foreach($item->details as $detail)
            <div class="d-flex align-items-center py-1 gap-2">
                <span class="text-secondary fw-medium text-nowrap">{{ $detail->quantity ?? 1 }} x </span>
                <span class="text-body flex-fill text-nowrap">{{ $detail->name ?? lang('igniterlabs.kitchendisplay::default.text_unknown') }}</span>
            </div>
            @if ($detail->menu_options->isNotEmpty())
                <ul class="list-unstyled small">
                    @foreach ($detail->menu_options as $itemOptionGroupName => $itemOptions)
                        <li>
                            <u class="text-muted">{{ $itemOptionGroupName }}:</u>
                            <ul class="list-unstyled">
                                @foreach ($itemOptions as $itemOption)
                                    <li>
                                        @if ($itemOption->quantity > 1)
                                            {{ $itemOption->quantity }} @lang('igniter.cart::default.text_times')
                                        @endif
                                        {{ $itemOption->order_option_name }}&nbsp;
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endforeach
                </ul>
            @endif
            @if (!empty($orderItem->comment))
                <p class="comment text-muted small">
                    {!! $orderItem->comment !!}
                </p>
            @endif

        @endforeach
    @else
        <div class="d-flex align-items-center py-1 text-muted">{{ lang('igniterlabs.kitchendisplay::default.text_no_items') }}</div>
    @endif
</div>
