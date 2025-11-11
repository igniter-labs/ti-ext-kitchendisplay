@if($order->order_type === 'delivery' && $order->address)
    <div class="order-address">
        {{ format_address($order->address, false) }}
    </div>
@endif
