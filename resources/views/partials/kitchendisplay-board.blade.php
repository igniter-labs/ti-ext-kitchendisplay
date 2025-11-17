<div id="board" class="board">
    @foreach($boardColumns as $boardColumn)
        <div class="board-column" data-status="new">
            <div class="column-header">
                <span>{{ $boardColumn['label'] }}</span>
                <span class="board-counter badge" style="background-color: {{ $boardColumn['color'] }}">
                    {{ count($boardColumn['orders']) }}
                </span>
            </div>

            <div class="order-list">
                @foreach($boardColumn['orders'] as $order)
                    <div class="order-card" data-order-id="{{ $order->order_id }}">
                        <div class="order-header"></div>
                        <div class="order-body">
                            @include('igniterlabs.kitchendisplay::partials.order-card-header', ['order' => $order, 'hiddenCardFields' => $hiddenCardFields ?? []])
                            @include('igniterlabs.kitchendisplay::partials.order-meta', ['order' => $order, 'hiddenCardFields' => $hiddenCardFields ?? []])
                            @include('igniterlabs.kitchendisplay::partials.order-type', ['order' => $order, 'hiddenCardFields' => $hiddenCardFields ?? []])
                            @include('igniterlabs.kitchendisplay::partials.order-items', ['order' => $order])
                            @include('igniterlabs.kitchendisplay::partials.order-actions', ['order' => $order, 'availableStatuses' => $availableStatuses, 'onHoldStatusId' => $onHoldStatusId ?? null])
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
