<div data-control="kitchen-display" class="p-4"
{{--     data-refresh-handler="{{ $this->getEventHandler('onRefresh') }}"--}}
>
    <!-- Filter bar -->
    <div class="filter-bar">
        <h4>Kitchen Display View</h4>
        <select id="statusFilter" class="form-select w-auto" multiple>
            <option value="new" selected>New</option>
            <option value="preparing" selected>Preparing</option>
            <option value="ready" selected>Ready to Collect</option>
            <option value="completed" selected>Completed</option>
            <option value="hold" selected>On Hold</option>
        </select>
    </div>

    <!-- Board -->
    <div id="board" class="board">

        <!-- Column Template -->
        @foreach($boardColumns as $boardColumn)
            <div class="board-column" data-status="new">
                <div class="column-header">
                    <span>{{ $boardColumn['label'] }}</span>
                    <span class="board-counter badge"
                          style="background-color: {{ $boardColumn['color'] }}">{{ count($boardColumn['orders']) }}</span>
                </div>
                <div class="order-list" data-status="{{ $boardColumn['status'] }}"
                     data-color="{{ $boardColumn['color'] }}">
                    @foreach($boardColumn['orders'] as $order)
                        <div class="order-card" data-order-id="{{ $order->order_id }}">
                            <div class="order-header"
                                 style="background-color: {{ $boardColumn['color'] }}"></div>
                            <div class="order-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <strong>Order #{{ $order->order_id }}</strong>
                                    <span class="status-label"
                                          style="background-color: {{ $boardColumn['color'] }}">
                                        {{ $order->status_name }}
                                    </span>
                                </div>
                                <div class="order-info mt-2">
                                    <div>{{ $order->customer_name }}</div>
                                    @if($order->address)
                                        <div>{{ format_address($order->address, false) }}</div>
                                    @endif
                                    <div>{{ $order->order_date->setTimeFromTimeString($order->order_time)->isoFormat(lang('system::lang.moment.date_time_format_short')) }}</div>
                                    <div>{{ currency_format($order->order_total) }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

    </div>
</div>
