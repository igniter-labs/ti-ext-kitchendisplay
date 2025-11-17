<div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orderDetailsModalLabel">Order Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="order-detail-section">
                    <div class="detail-row">
                        <span class="detail-label">Order:</span>
                        <span class="detail-value" id="modalOrderNumber"></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Customer:</span>
                        <span class="detail-value" id="modalCustomerName"></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Status:</span>
                        <span class="detail-value" id="modalStatus"></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Ready Time:</span>
                        <span class="detail-value" id="modalOrderTime"></span>
                    </div>
                </div>
                <div class="items-section mt-3">
                    <h6 class="items-title">Items</h6>
                    <div class="items-list" id="modalItemsList"></div>
                </div>
            </div>
        </div>
    </div>
</div>
