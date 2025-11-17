<div class="order-card-header">
    <div class="order-title-section">
        @if(!in_array('order_id', $hiddenCardFields))
            <button class="order-title-btn" type="button" data-order-id="{{ $order->order_id }}" title="View order details">
                <strong>#{{ $order->order_id }}</strong>
            </button>
        @endif
        <span class="status-label">{{ $order->status_name }}</span>
    </div>
    <button class="expand-card-btn" type="button" data-order-id="{{ $order->order_id }}">
        <i class="fa fa-chevron-down"></i>
    </button>
</div>
