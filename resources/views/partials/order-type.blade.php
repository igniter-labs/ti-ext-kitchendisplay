@if(!in_array('order_type', $hiddenCardFields))
    <div class="order-type-badge">
        <i class="fa fa-{{ $order->order_type_icon }}"></i>
        {{ $order->order_type_display }}
    </div>
@endif
