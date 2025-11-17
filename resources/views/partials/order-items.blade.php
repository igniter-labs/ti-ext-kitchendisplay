<div class="order-items-container" style="display: none;">
    <div class="order-items">
        @php
            $items = is_array($order->cart) ? $order->cart : json_decode($order->cart, true);
        @endphp
        @if($items && is_array($items))
            @foreach($items as $item)
                <div class="item-row">
                    <span class="item-qty">{{ $item['quantity'] ?? 1 }} x </span>
                    <span class="item-name">{{ $item['name'] ?? 'Unknown' }}</span>
                </div>
            @endforeach
        @else
            <div class="item-row text-muted">No items</div>
        @endif
    </div>
</div>
