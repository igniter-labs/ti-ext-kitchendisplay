<div class="modal fade" id="customTimeModal" tabindex="-1" aria-labelledby="customTimeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="customTimeModalLabel">Set Custom Wait Time</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="customTimeInput" class="form-label">Order Time (HH:MM)</label>
                    <input type="time" class="form-control" id="customTimeInput">
                    <small class="text-muted">Enter the new ready time for this order</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveCustomTimeBtn">Save</button>
            </div>
        </div>
    </div>
</div>
