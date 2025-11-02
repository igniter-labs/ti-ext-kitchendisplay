<div data-control="kitchen-display" class="p-4">
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
        <div class="board-column" data-status="new">
            <div class="column-header">
                <span>New</span>
                <span class="badge bg-primary">2</span>
            </div>
            <div class="order-list">
                <div class="order-card status-new">
                    <div class="order-header"></div>
                    <div class="order-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <strong>Order #1001</strong>
                            <span class="status-label">New</span>
                        </div>
                        <div class="order-info mt-2">
                            <div>John Doe</div>
                            <div>123 Main St</div>
                            <div>12:30 PM</div>
                            <div>$25.00</div>
                        </div>
                        <div class="mt-2">
                            <select class="form-select form-select-sm">
                                <option>Assign user</option>
                                <option>Rachel</option>
                                <option>Mike</option>
                            </select>
                        </div>
                        <div class="mt-2">
                            <button class="btn btn-outline-primary btn-sm w-100" data-bs-toggle="modal"
                                    data-bs-target="#viewOrderModal">View Order
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Preparing -->
        <div class="board-column" data-status="preparing">
            <div class="column-header">
                <span>Preparing</span>
                <span class="badge bg-warning text-dark">3</span>
            </div>
            <div class="order-list">
                <div class="order-card status-preparing">
                    <div class="order-header"></div>
                    <div class="order-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <strong>Order #1002</strong>
                            <span class="status-label">Preparing</span>
                        </div>
                        <div class="order-info mt-2">
                            <div>Mary Smith</div>
                            <div>456 Elm St</div>
                            <div>1:00 PM</div>
                            <div>$32.00</div>
                        </div>
                        <div class="mt-2">
                            <select class="form-select form-select-sm">
                                <option>Assign user</option>
                                <option>Rachel</option>
                                <option>Mike</option>
                            </select>
                        </div>
                        <div class="mt-2">
                            <button class="btn btn-outline-warning btn-sm w-100" data-bs-toggle="modal"
                                    data-bs-target="#viewOrderModal">View Order
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ready -->
        <div class="board-column" data-status="ready">
            <div class="column-header">
                <span>Ready to Collect</span>
                <span class="badge bg-success">1</span>
            </div>
            <div class="order-list">
                <div class="order-card status-ready">
                    <div class="order-header"></div>
                    <div class="order-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <strong>Order #1003</strong>
                            <span class="status-label">Ready</span>
                        </div>
                        <div class="order-info mt-2">
                            <div>Tony Lee</div>
                            <div>890 Oak St</div>
                            <div>1:30 PM</div>
                            <div>$18.00</div>
                        </div>
                        <div class="mt-2">
                            <select class="form-select form-select-sm">
                                <option>Assign user</option>
                                <option>Rachel</option>
                                <option>Mike</option>
                            </select>
                        </div>
                        <div class="mt-2">
                            <button class="btn btn-outline-success btn-sm w-100" data-bs-toggle="modal"
                                    data-bs-target="#viewOrderModal">View Order
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Completed -->
        <div class="board-column" data-status="completed">
            <div class="column-header">
                <span>Completed</span>
                <span class="badge bg-success">2</span>
            </div>
            <div class="order-list">
                <div class="order-card status-completed">
                    <div class="order-header"></div>
                    <div class="order-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <strong>Order #1004</strong>
                            <span class="status-label">Completed</span>
                        </div>
                        <div class="order-info mt-2">
                            <div>Olivia Brown</div>
                            <div>321 Pine St</div>
                            <div>2:45 PM</div>
                            <div>$40.00</div>
                        </div>
                        <div class="mt-2">
                            <select class="form-select form-select-sm">
                                <option>Assign user</option>
                                <option>Rachel</option>
                                <option>Mike</option>
                            </select>
                        </div>
                        <div class="mt-2">
                            <button class="btn btn-outline-success btn-sm w-100" data-bs-toggle="modal"
                                    data-bs-target="#viewOrderModal">View Order
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- On Hold -->
        <div class="board-column" data-status="hold">
            <div class="column-header">
                <span>On Hold</span>
                <span class="badge bg-danger">1</span>
            </div>
            <div class="order-card status-hold">
                <div class="order-header"></div>
                <div class="order-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <strong>Order #1005</strong>
                        <span class="status-label">On Hold</span>
                    </div>
                    <div class="order-info mt-2">
                        <div>Mark Johnson</div>
                        <div>654 Cedar Ave</div>
                        <div>3:30 PM</div>
                        <div>$22.00</div>
                    </div>
                    <div class="mt-2">
                        <select class="form-select form-select-sm">
                            <option>Assign user</option>
                            <option>Rachel</option>
                            <option>Mike</option>
                        </select>
                    </div>
                    <div class="mt-2">
                        <button class="btn btn-outline-danger btn-sm w-100" data-bs-toggle="modal"
                                data-bs-target="#viewOrderModal">View Order
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Modal -->
    <div class="modal fade" id="viewOrderModal" tabindex="-1" aria-labelledby="viewOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewOrderModalLabel">Order Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Order information will appear here...</p>
                </div>
            </div>
        </div>
    </div>
</div>
