<div class="bg-white border rounded mb-3 shadow-sm position-relative overflow-visible" data-order-id="{{ $item->id }}">
    <div class="p-3">
        <div class="d-flex justify-content-between align-items-center mb-2 gap-2">
            <div class="d-flex align-items-center gap-2 flex-fill">
                    <button
                        class="order-title-btn bg-transparent border-0 p-0 text-decoration-none fw-bold"
                        type="button"
                        title="{{ lang('igniterlabs.kitchendisplay::default.text_view_item_details') }}"
                        data-bs-toggle="modal"
                        data-bs-target="#card-details-modal-{{ $item->id }}"
                    >
                        @if(!$this->isHiddenCardField('order_id'))
                            <strong>#{{ $item->id }}</strong>
                        @else
                            <strong>###</strong>
                        @endif
                    </button>
                <span class="badge bg-secondary-subtle text-dark">{{ $item->statusName }}</span>
            </div>
            <button
                data-toggle="card-expand"
                class="expand-card-btn bg-transparent border-0 p-1 text-secondary"
                type="button"
                data-expand-target="#card-items-{{$item->id}}"
            ><i class="fa fa-chevron-down"></i></button>
        </div>
        <div class="mb-1">
            <div class="d-flex justify-content-between align-items-center gap-2 small text-secondary">
                @if(!$this->isHiddenCardField('customer_name'))
                    <span class="fw-medium text-body flex-fill">{{ $item->customerName }}</span>
                @endif
                <div class="dropdown">
                    <button
                        class="btn bg-transparent border-0 p-0 text-decoration-underline fw-bold link-offset-3 font-monospace text-primary"
                        type="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                        data-order-time="{{ $item->time }}"
                        title="{{ lang('igniterlabs.kitchendisplay::default.text_click_to_adjust_time') }}"
                    >
                        {{ $item->formattedTime }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        @foreach($this->waitTimes as $minutes)
                            <li>
                                <a
                                    class="dropdown-item"
                                    href="#"
                                    data-control="wait-time"
                                    data-item-id="{{ $item->id }}"
                                    data-minutes="{{ $minutes }}"
                                >{{sprintf('+%s %s', $minutes, lang('igniter::main.text_min'))}}</a>
                            </li>
                        @endforeach
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a
                                class="dropdown-item wait-time-custom"
                                href="#"
                                data-bs-toggle="modal"
                                data-bs-target="#custom-time-modal-{{ $item->id }}"
                            >{{ lang('igniterlabs.kitchendisplay::default.text_custom') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        @if(!$this->isHiddenCardField('order_type'))
            <div class="d-flex align-items-center gap-1 small text-secondary text-nowrap p-1 mb-1">
                <i class="fa fa-{{ $item->type === 'delivery' ? 'truck' : 'shopping-bag' }}"></i>
                {{ $item->typeName }}
            </div>
        @endif
        <div id="card-items-{{$item->id}}" class="py-2 border-top" style="display: none;">
            {!! $this->makePartial('kitchendisplay/card-details', ['item' => $item]) !!}
        </div>
        <div class="d-flex gap-2 align-items-stretch border-top pt-2">
            {!! $this->makePartial('kitchendisplay/card-actions', ['item' => $item]) !!}
        </div>
    </div>
</div>
