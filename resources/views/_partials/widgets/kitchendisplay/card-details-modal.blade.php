<div class="modal fade" id="card-details-modal-{{ $item->id }}" tabindex="-1"
    aria-labelledby="card-details-modal-label-{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"
                    id="card-details-modal-label-{{ $item->id }}"
                >{{ lang('igniterlabs.kitchendisplay::default.text_order_details_title') }}</h5>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="{{ lang('igniterlabs.kitchendisplay::default.text_close') }}"
                ></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between py-2 small border-bottom">
                        <span class="text-muted">{{ lang('igniterlabs.kitchendisplay::default.text_order_label') }}</span>
                        <span class="text-body fw-medium text-end flex-fill">{{ $item->id }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2 small border-bottom">
                        <span class="text-muted">{{ lang('igniterlabs.kitchendisplay::default.text_customer_label') }}</span>
                        <span class="text-body fw-medium text-end flex-fill">{{ $item->customerName }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2 small border-bottom">
                        <span class="text-muted">{{ lang('igniterlabs.kitchendisplay::default.text_status_label') }}</span>
                        <span class="text-body fw-medium text-end flex-fill">{{ $item->statusName }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2 small">
                        <span class="text-muted">{{ lang('igniterlabs.kitchendisplay::default.text_ready_time_label') }}</span>
                        <span class="text-body fw-medium text-end flex-fill">{{ $item->time }}</span>
                    </div>
                </div>
                <div class="border-top pt-3 mt-3">
                    <h6 class="text-muted mb-3 small">{{ lang('igniterlabs.kitchendisplay::default.text_items_title') }}</h6>
                    {!! $this->makePartial('kitchendisplay/card-details', ['item' => $item]) !!}
                </div>
            </div>
        </div>
    </div>
</div>
