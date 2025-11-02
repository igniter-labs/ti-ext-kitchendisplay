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

    KitchenDisplay.prototype.init = function() {
        // Enable drag-and-drop using SortableJS
        let $this = this;

        $this.initSortable();

        // Multi-select filtering logic
        const filter = document.getElementById('statusFilter');
        const columns = document.querySelectorAll('.board-column');

        filter.addEventListener('change', () => {
            const selected = Array.from(filter.selectedOptions).map(o => o.value);
            columns.forEach(col => {
                const status = col.getAttribute('data-status');
                col.style.display = selected.includes(status) ? 'block' : 'none';
            });
        });

        // Initialization code for KitchenDisplay can be added here
        $this.hideSidebar();

        Broadcast.channel('igniterlabs.kitchendisplay')
            .listen('.kitchendisplay.updated', (e) => {
                $.request('onRefreshOrders', {
                    success: function(data) {
                        $('[data-control="kitchen-display"]').parent().html(data.result)
                        $this.initSortable();
                    }
                })
                // console.log('refreshed')
            })
    }

    KitchenDisplay.prototype.initSortable = function() {
        let $this = this;
        document.querySelectorAll('.order-list').forEach(column => {
            new Sortable(column, {
                group: 'shared',
                animation: 150,
                ghostClass: 'sortable-ghost',
                onEnd: function(evt) {
                    if(evt.from !== evt.to) {
                        let item = evt.item;
                        let fromColumn = evt.from;
                        let toColumn = evt.to;
                        let orderId = item.getAttribute('data-order-id');
                        let columnStatus = toColumn.getAttribute('data-status');
                        let columnColor = toColumn.getAttribute('data-color');

                        // Send AJAX request to update order status and positions
                        $this.updateOrder(orderId, columnStatus,
                            function() {
                                let $orderHeader = $(item).find('.order-header');
                                if($orderHeader.length) {
                                    $orderHeader.css('background-color', columnColor);
                                }
                                let statusLabel = $(item).find('.status-label');
                                if(statusLabel.length) {
                                    statusLabel.text(columnStatus);
                                    statusLabel.css('background-color', columnColor);
                                }

                                let fromStatusCounter = $(fromColumn).closest('.board-column').find('.board-counter');
                                let toStatusCounter = $(toColumn).closest('.board-column').find('.board-counter');
                                if(toStatusCounter.length && fromStatusCounter.length) {
                                    let fromCount = parseInt(fromStatusCounter.text()) - 1;
                                    let toCount = parseInt(toStatusCounter.text()) + 1;
                                    fromStatusCounter.text(fromCount);
                                    toStatusCounter.text(toCount);
                                }

                            },
                            function() {
                                // On error, move the item back to its original position
                                fromColumn.insertBefore(item, fromColumn.children[evt.oldIndex]);
                            }
                        );
                    }
                }
            });
        });
    }

    KitchenDisplay.prototype.updateOrder = function(orderId, newStatus, onSuccess = null, onError = null) {
        $.request('onUpdateOrderStatus', {
            data: {
                order_id: orderId,
                status: newStatus
            },
            success: onSuccess,
            error: onError
        });
    }

    KitchenDisplay.prototype.hideSidebar = function() {
        if (window.innerWidth >= 992) {
            let sidebar = $('.sidebar').first();
            let sidebarToggleBtn = $('.navbar-brand > .nav-link')
            sidebarToggleBtn.removeClass('d-lg-none');

            if(localStorage.getItem('kitchenDisplaySidebar') === 'hidden') {
                sidebar.addClass('d-lg-none');
            } else {
                sidebar.removeClass('d-lg-none');
            }

            // restrict to only laptop screens
            sidebarToggleBtn.on('click', function() {
                sidebar.toggleClass('d-lg-none');
                // store to localStorage
                if (sidebar.hasClass('d-lg-none')) {
                    localStorage.setItem('kitchenDisplaySidebar', 'hidden');
                } else {
                    $('#sidebarMenu + .offcanvas-backdrop').remove(); // remove backdrop if present
                    localStorage.setItem('kitchenDisplaySidebar', 'visible');
                }
            });
        }
    }

    var old = $.fn.kitchenDisplay;

    $.fn.kitchenDisplay = function(option) {
        var args = Array.prototype.slice.call(arguments, 1),
            result = undefined;

        this.each(function() {
            var $this = $(this);
            var data = $this.data('ti.kitchenDisplay');
            var options = $.extend({}, KitchenDisplay.DEFAULTS, $this.data(), typeof option == 'object' && option);
            if (!data) $this.data('ti.kitchenDisplay', (data = new KitchenDisplay(this, options)));
            if (typeof option == 'string') result = data[option].apply(data, args);
            if (typeof result != 'undefined') return false;
        });

        return result ? result : this;
    }

    $.fn.kitchenDisplay.Constructor = KitchenDisplay;

    $.fn.kitchenDisplay.noConflict = function() {
        $.fn.kitchenDisplay = old;
        return this;
    }

    $(document).render(function() {
        $('[data-control="kitchen-display"]').kitchenDisplay();
    })
}(window.jQuery);
