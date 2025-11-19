@if($item->statusId != $onHoldStatusId)
    <button
        class="btn btn-light border d-flex align-items-center justify-content-center flex-shrink-0 text-muted"
        type="button"
        data-control="item-status"
        data-item-id="{{ $item->id }}"
        data-status-id="0"
    >
        <i class="fa fa-pause"></i>
    </button>
@endif
<div class="dropdown flex-fill">
    <button
        class="btn btn-primary dropdown-toggle w-100 small fw-medium"
        type="button"
        data-bs-toggle="dropdown"
        data-item-id="{{ $item->id }}"
        aria-expanded="false"
    >
        {{ lang('igniterlabs.kitchendisplay::default.text_next') }}
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
