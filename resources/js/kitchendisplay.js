/**
 * Kitchen Display System - jQuery Plugin
 */
+function($) {
    "use strict";

    if ($.fn === undefined) $.fn = {};
    if ($.fn.kitchenDisplay === undefined)
        $.fn.kitchenDisplay = {};

    var KitchenDisplay = function(element, options) {
        this.$el = $(element);
        this.options = options;
        this.init();
    }

    KitchenDisplay.prototype.constructor = KitchenDisplay;

    // Refresh orders while preserving both vertical and horizontal scroll positions
    KitchenDisplay.prototype.refreshOrdersWithScrollRestore = function(callback) {
        let $this = this;
        // Store current scroll positions before DOM update
        let scrollTop = $(window).scrollTop();
        let $board = $('[data-control="kitchen-display"]').find('.board');
        let scrollLeft = $board.scrollLeft();
        let $container = $('[data-control="kitchen-display"]');

        $.request('onRefreshOrders', {
            data: {
                kitchenDisplayId: $container.data('kitchen-display-id')
            },
            success: function(data) {
                // Update the board content
                $container.html(data.result);
                // Restore scroll positions after content is rendered
                $(window).scrollTop(scrollTop);
                $container.find('.board').scrollLeft(scrollLeft);
                if (callback) callback();
            }
        });
    };

    KitchenDisplay.prototype.init = function() {
        let $this = this;

        $this.initWaitTimeHandlers();
        $this.hideSidebar();
        $this.initRefreshButton();

        Broadcast.channel('igniterlabs.kitchendisplay')
            .listen('.kitchendisplay.updated', (e) => {
                $this.refreshOrdersWithScrollRestore();
            })
    }

    KitchenDisplay.prototype.updateOrderStatus = function(orderId, statusId) {
        let $this = this;
        $.request('onUpdateOrderStatus', {
            data: {
                order_id: orderId,
                status: statusId
            },
            success: function() {
                $this.refreshOrdersWithScrollRestore();
            },
            error: function() {
                alert('Failed to update order status');
            }
        });
    }

    KitchenDisplay.prototype.initWaitTimeHandlers = function() {
        let $this = this;

        // Raise z-index when dropdown opens to prevent it from being hidden behind adjacent cards
        $(document).on('show.bs.dropdown', '.dropdown', function(e) {
            let $card = $(this).closest('.order-card');
            $card.addClass('dropdown-open').css('z-index', '999');
        });

        // Reset z-index when dropdown closes
        $(document).on('hide.bs.dropdown', '.dropdown', function(e) {
            let $card = $(this).closest('.order-card');
            $card.removeClass('dropdown-open').css('z-index', '');
        });

        $(document).on('click', '.expand-card-btn', function() {
            let $btn = $(this);
            let $card = $btn.closest('.order-card');
            let $itemsContainer = $card.find('.order-items-container');

            $btn.toggleClass('expanded');
            $itemsContainer.slideToggle(200);
        });

        $(document).on('click', '.order-title-btn', function() {
            let orderId = $(this).data('order-id');
            $this.showOrderDetailsModal(orderId);
        });

        $(document).on('click', '.wait-time-option', function() {
            let $btn = $(this);
            let minutes = $btn.data('minutes');
            let $card = $btn.closest('.order-card');
            let orderId = $card.data('order-id');

            $this.updateWaitTime(orderId, minutes, null);
        });

        $(document).on('click', '.wait-time-custom', function() {
            let $btn = $(this);
            let $card = $btn.closest('.order-card');
            let orderId = $card.data('order-id');
            let $timeBtn = $card.find('.order-time-editable');
            let currentTime = $timeBtn.data('order-time');

            let timeHHMM = currentTime ? currentTime.substring(0, 5) : '';
            $this.showCustomTimeModal(orderId, timeHHMM);
        });

        $(document).on('click', '.hold-btn', function() {
            let $btn = $(this);
            let orderId = $btn.data('order-id');
            let $container = $('[data-control="kitchen-display"]');
            $this.updateOrderStatus(orderId, $container.data('on-hold-status-id'));
        });

        $(document).on('click', '.status-option', function() {
            let $option = $(this);
            let statusId = $option.data('status');
            let orderId = $option.data('order-id');

            $this.updateOrderStatus(orderId, statusId);
        });
    }

    KitchenDisplay.prototype.initRefreshButton = function() {
        let $this = this;

        $(document).on('click', '.refresh-orders-btn', function() {
            let $btn = $(this);

            $btn.prop('disabled', true).addClass('disabled');
            $btn.find('i').addClass('fa-spin');

            $this.refreshOrdersWithScrollRestore(function() {
                $btn.prop('disabled', false).removeClass('disabled');
                $btn.find('i').removeClass('fa-spin');
            });
        });
    }

    KitchenDisplay.prototype.updateWaitTime = function(orderId, minutes, customTime) {
        let $this = this;
        let data = {
            order_id: orderId,
            minutes: minutes
        };

        if (customTime) {
            data.custom_time = customTime;
            data.minutes = null;
        }

        $.request('onUpdateWaitTime', {
            data: data,
            success: function() {
                $this.refreshOrdersWithScrollRestore();
            },
            error: function() {
                alert('Failed to update wait time');
            }
        });
    }

    KitchenDisplay.prototype.showCustomTimeModal = function(orderId, currentTime) {
        let $this = this;
        let $modal = $('#customTimeModal');
        let $timeInput = $modal.find('#customTimeInput');

        $timeInput.val(currentTime);
        // Clear previous click handlers to prevent duplicate events
        $modal.find('#saveCustomTimeBtn').off('click');

        $modal.find('#saveCustomTimeBtn').on('click', function() {
            let customTime = $timeInput.val();
            if (customTime) {
                $this.updateWaitTime(orderId, null, customTime);
                // Get existing modal instance (created on first call) and hide it
                let existingModal = bootstrap.Modal.getInstance($modal[0]);
                if (existingModal) {
                    existingModal.hide();
                }
            }
        });

        // Reuse existing modal instance if it exists, otherwise create new one
        let existingModal = bootstrap.Modal.getInstance($modal[0]);
        if (existingModal) {
            existingModal.show();
        } else {
            let modal = new bootstrap.Modal($modal[0]);
            modal.show();
        }
    }

    KitchenDisplay.prototype.showOrderDetailsModal = function(orderId) {
        // Find the order card element in the DOM
        let $card = $('[data-order-id="' + orderId + '"]');
        if ($card.length === 0) return;

        // Extract order data from the card's DOM elements
        let orderNumber = $card.find('.order-title-btn strong').text();
        let customerName = $card.find('.customer-name').text();
        let orderTime = $card.find('.order-time-editable').text();
        let status = $card.find('.status-label').text();
        let itemsHtml = $card.find('.order-items').html();

        let $modal = $('#orderDetailsModal');

        // Show or hide order number row based on whether data is available
        if (orderNumber) {
            $modal.find('#modalOrderNumber').text(orderNumber).closest('.detail-row').show();
        } else {
            $modal.find('#modalOrderNumber').closest('.detail-row').hide();
        }

        // Show or hide customer name row based on whether data is available
        if (customerName) {
            $modal.find('#modalCustomerName').text(customerName).closest('.detail-row').show();
        } else {
            $modal.find('#modalCustomerName').closest('.detail-row').hide();
        }

        // Populate modal with order information
        $modal.find('#modalStatus').text(status);
        $modal.find('#modalOrderTime').text(orderTime);
        $modal.find('#modalItemsList').html(itemsHtml || '<div class="text-muted">No items</div>');

        // Reuse existing modal instance if available, otherwise create new one
        let existingModal = bootstrap.Modal.getInstance($modal[0]);
        if (existingModal) {
            existingModal.show();
        } else {
            let modal = new bootstrap.Modal($modal[0]);
            modal.show();
        }
    }

    // Toggle sidebar visibility only on desktop screens (992px+), persisting user preference to localStorage
    KitchenDisplay.prototype.hideSidebar = function() {
        // Only apply sidebar toggle to desktop views
        if (window.innerWidth >= 992) {
            let sidebar = $('.sidebar').first();
            let sidebarToggleBtn = $('.navbar-brand > .nav-link');

            sidebarToggleBtn.removeClass('d-lg-none');

            // Load saved user preference from localStorage
            if (localStorage.getItem('kitchenDisplaySidebar') === 'hidden') {
                sidebar.addClass('d-lg-none');
            } else {
                sidebar.removeClass('d-lg-none');
            }

            sidebarToggleBtn.on('click', function() {
                sidebar.toggleClass('d-lg-none');

                // Save user preference to localStorage for future visits
                if (sidebar.hasClass('d-lg-none')) {
                    localStorage.setItem('kitchenDisplaySidebar', 'hidden');
                } else {
                    // Clean up any leftover backdrop elements from previous interactions
                    $('#sidebarMenu + .offcanvas-backdrop').remove();
                    localStorage.setItem('kitchenDisplaySidebar', 'visible');
                }
            });
        }
    }

    // Save reference to existing plugin definition to support noConflict()
    var old = $.fn.kitchenDisplay;

    // jQuery plugin interface - allows calling plugin methods via jQuery syntax
    $.fn.kitchenDisplay = function(option) {
        // Capture all arguments after the first one to pass to plugin methods
        var args = Array.prototype.slice.call(arguments, 1),
            result = undefined;

        this.each(function() {
            var $this = $(this);
            var data = $this.data('ti.kitchenDisplay');
            // Merge default options with element data attributes and passed options
            var options = $.extend({}, KitchenDisplay.DEFAULTS, $this.data(), typeof option == 'object' && option);

            // Initialize plugin if not already initialized on this element
            if (!data) $this.data('ti.kitchenDisplay', (data = new KitchenDisplay(this, options)));
            // If option is a string, call that method on the plugin instance
            if (typeof option == 'string') result = data[option].apply(data, args);
            // Return false if a method returned a value to break the chain
            if (typeof result != 'undefined') return false;
        });

        return result ? result : this;
    }

    // Expose the constructor for extension/inheritance
    $.fn.kitchenDisplay.Constructor = KitchenDisplay;

    // Restore previous plugin definition if needed (allows avoiding naming conflicts)
    $.fn.kitchenDisplay.noConflict = function() {
        $.fn.kitchenDisplay = old;
        return this;
    }

    // Auto-initialize plugin when DOM is ready (using custom render event)
    $(document).render(function() {
        $('[data-control="kitchen-display"]').kitchenDisplay();
    })
}(window.jQuery);
