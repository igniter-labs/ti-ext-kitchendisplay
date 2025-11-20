<button
    class="btn btn-light border"
    type="button"
    data-control="item-status"
    data-item-id="{{ $item->id }}"
    data-status-id="{{$item->statusId != $onHoldStatusId ? $onHoldStatusId : $this->getNextStatusId($item->statusId)}}"
>
    <i class="fa {{$item->statusId != $onHoldStatusId ? 'fa-pause text-muted' : 'fa-play text-success'}}"></i>
</button>
<div class="btn-group w-100">
    <button
        type="button"
        class="btn btn-primary small fw-medium rounded-end-0 w-100"
        data-control="item-status"
        data-item-id="{{ $item->id }}"
        data-status-id="{{ $this->getNextStatusId($item->statusId) }}"
    >
        {{ lang('igniterlabs.kitchendisplay::default.text_next') }}&nbsp;&nbsp;&nbsp;
        <i class="fa fa-arrow-right-long"></i>
    </button>
    <button
        type="button"
        class="btn btn-primary dropdown-toggle dropdown-toggle-split rounded-start-0"
        data-bs-toggle="dropdown"
        aria-expanded="false"
    ><span class="visually-hidden">Toggle Dropdown</span>
    </button>
    <ul class="dropdown-menu dropdown-menu-end">
        @forelse($this->getCardStatuses($item->statusId) as $statusId => $statusName)
            <li>
                <a
                    class="dropdown-item status-option"
                    href="#"
                    data-control="item-status"
                    data-item-id="{{ $item->id }}"
                    data-status-id="{{ $statusId }}"
                >
                    {{ $statusName }}
                </a>
            </li>
        @empty
            <li><a class="dropdown-item disabled" href="#">{{ lang('igniterlabs.kitchendisplay::default.text_no_options_available') }}</a></li>
        @endforelse
    </ul>
</div>
