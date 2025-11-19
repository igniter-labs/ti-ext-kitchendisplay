<div class="modal fade" id="custom-time-modal-{{ $item->id }}" tabindex="-1" aria-labelledby="custom-time-modal-label-{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5
                    class="modal-title"
                    id="custom-time-modal-label-{{ $item->id }}"
                >{{ lang('igniterlabs.kitchendisplay::default.text_custom_time_title') }}</h5>
                <button
                    type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="{{ lang('igniterlabs.kitchendisplay::default.text_close') }}"
                ></button>
            </div>
            {!! form_open([
                 'id'     => 'custom-time-form',
                 'role'   => 'form',
                 'method' => 'POST',
                'data-request' => $this->alias.'::onUpdateWaitTime',
             ]) !!}
            <div class="modal-body">
                <div class="mb-3">
                    <label
                        for="customTimeInput"
                        class="form-label"
                    >{{ lang('igniterlabs.kitchendisplay::default.text_order_time_label') }}</label>
                    <input type="hidden" name="itemId" class="form-control" value="{{ $item->id }}">
                    <input
                        type="time"
                        name="customTime"
                        class="form-control"
                        pattern="[0-9]{2}:[0-9]{2}"
                        value="{{ $item->time }}" />
                    <small
                        class="text-muted"
                    >{{ lang('igniterlabs.kitchendisplay::default.text_order_time_help') }}</small>
                </div>
            </div>
            <div class="modal-footer">
                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal"
                >{{ lang('igniterlabs.kitchendisplay::default.text_cancel') }}</button>
                <button
                    type="button"
                    class="btn btn-primary"
                    id="saveCustomTimeBtn"
                >{{ lang('igniterlabs.kitchendisplay::default.text_save') }}</button>
            </div>
            {!! form_close() !!}
        </div>
    </div>
</div>
