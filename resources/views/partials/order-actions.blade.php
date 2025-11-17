<div class="order-action-buttons">
    @if($order->status_id !== ($onHoldStatusId ?? null))
        <button class="hold-btn btn btn-light border" type="button" data-order-id="{{ $order->order_id }}">
            <i class="fa fa-pause"></i>
        </button>
    @endif
    <div class="next-btn-container dropdown">
        <button class="next-btn btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" data-order-id="{{ $order->order_id }}" aria-expanded="false">
            Next
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
            @forelse($order->filtered_statuses as $statusId => $statusName)
                <li>
                    <a class="dropdown-item status-option" href="#" data-status="{{ $statusId }}" data-order-id="{{ $order->order_id }}">
                        {{ $statusName }}
                    </a>
                </li>
            @empty
                <li><a class="dropdown-item disabled" href="#">No options available</a></li>
            @endforelse
        </ul>
    </div>
</div>
