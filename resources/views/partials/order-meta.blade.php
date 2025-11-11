<div class="order-meta">
    <div class="order-meta-row">
        @if(!in_array('customer_name', $hiddenCardFields))
            <span class="customer-name">{{ $order->customer_name }}</span>
        @endif
        <div class="dropdown">
            <button class="order-time-editable btn" type="button" data-bs-toggle="dropdown" aria-expanded="false" data-order-time="{{ $order->order_time }}" title="Click to adjust ready time">
                {{ $order->formatted_time }}
            </button>
            <ul class="dropdown-menu dropdown-menu-end order-time-dropdown">
                <li><a class="dropdown-item wait-time-option" href="#" data-minutes="5">+5 min</a></li>
                <li><a class="dropdown-item wait-time-option" href="#" data-minutes="10">+10 min</a></li>
                <li><a class="dropdown-item wait-time-option" href="#" data-minutes="15">+15 min</a></li>
                <li><a class="dropdown-item wait-time-option" href="#" data-minutes="20">+20 min</a></li>
                <li><a class="dropdown-item wait-time-option" href="#" data-minutes="30">+30 min</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item wait-time-custom" href="#">Custom</a></li>
            </ul>
        </div>
    </div>
</div>
