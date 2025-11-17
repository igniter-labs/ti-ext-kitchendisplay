<div class="d-flex justify-content-between align-items-center p-3">
    <div class="d-flex align-items-center gap-2">
        @if($previousUrl = AdminMenu::getPreviousUrl())
            <a
                class="btn shadow-none border-none ps-0"
                href="{{$previousUrl}}"
            ><i class="fa fa-angle-left fs-4 align-bottom"></i></a>
        @endif
        <h4 class="page-title mb-0 lh-base">
            <span>{!! Template::getHeading() !!}</span>
        </h4>
    </div>
    <button class="btn btn-sm btn-outline-secondary refresh-orders-btn" title="Refresh orders">
        <i class="fa fa-refresh"></i>
    </button>
</div>
<div
    data-control="kitchen-display"
    data-kitchen-display-id="{{ $kitchenDisplayId }}"
    data-on-hold-status-id="{{ $boardColumns[4]['status_id'] ?? 4 }}"
    class="p-4"
>
    @include('igniterlabs.kitchendisplay::partials.kitchendisplay-board', [
        'boardColumns' => $boardColumns,
        'availableStatuses' => $availableStatuses,
        'hiddenCardFields' => $hiddenCardFields ?? [],
    ])
</div>

<!-- Modals -->
@include('igniterlabs.kitchendisplay::modals.custom-time-modal')
@include('igniterlabs.kitchendisplay::modals.order-details-modal')
