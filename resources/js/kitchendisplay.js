/**
 * Kitchen Display System - jQuery Plugin
 */
+function ($) {
    "use strict";

    if ($.fn === undefined) $.fn = {};
    if ($.fn.kitchenDisplay === undefined)
        $.fn.kitchenDisplay = {};

    var KitchenDisplay = function (element, options) {
        this.$el = $(element);
        this.options = options;
        this.init();
    }

    KitchenDisplay.prototype.constructor = KitchenDisplay;

    KitchenDisplay.prototype.init = function () {
        this.initDropdownHandlers();
        this.initViewPreference();

        $(document).on('click', '[data-control="refresh-items"]', $.proxy(this.onRefreshButtonClick, this));
        $(document).on('click', '[data-toggle="full-screen"]', $.proxy(this.onToggleFullscreen, this));
        $(document).on('click', '[data-control="switch-view"]', $.proxy(this.onSwitchView, this));

        if (localStorage.getItem('kitchenDisplayFullPage') === 'true') {
            $('body').addClass('kitchen-display-full-page');
        }

        this.$el.on('click', '[data-control="wait-time"]', $.proxy(this.onWaitTimeClick, this));
        this.$el.on('click', '[data-control="item-status"]', $.proxy(this.onStatusUpdateClick, this));
        this.$el.on('click', '[data-toggle="card-expand"]', $.proxy(this.onExpandCardClick, this));

        if (typeof Broadcast === 'undefined') {
            console.warn('Broadcast is not available. Kitchen display real-time updates are disabled.');
            return;
        }

        let $this = this;
        Broadcast.channel('igniterlabs.kitchendisplay')
            .listen('.kitchendisplay.updated', (e) => {
                $this.refreshItemsWithScrollRestore();
            })
    }

    KitchenDisplay.prototype.initDropdownHandlers = function () {
        // Raise z-index when dropdown opens to prevent it from being hidden behind adjacent cards
        this.$el.on('show.bs.dropdown', '.dropdown', function (e) {
            let $card = $(this).closest('.order-card');
            $card.addClass('dropdown-open').css('z-index', '999');
        });

        // Reset z-index when dropdown closes
        this.$el.on('hide.bs.dropdown', '.dropdown', function (e) {
            let $card = $(this).closest('.order-card');
            $card.removeClass('dropdown-open').css('z-index', '');
        });
    }

    KitchenDisplay.prototype.onStatusUpdateClick = function (event) {
        const $el = $(event.currentTarget);

        $.request('onUpdateOrderStatus', {
            data: {
                itemId: $el.data('itemId'),
                statusId: $el.data('statusId')
            }
        });
    }

    KitchenDisplay.prototype.initViewPreference = function () {
        const currentView = this.$el.data('viewMode');
        $('button[data-view="' + currentView + '"]').addClass('d-none');
        $('button[data-view="' + (currentView == 'board' ? 'list' : 'board') + '"]').removeClass('d-none');
    }

    KitchenDisplay.prototype.onSwitchView = function (event) {
        const $btn = $(event.currentTarget);
        $.request('onViewToggle', {
            data: {
                view: $btn.data('view')
            }
        });
    }

    KitchenDisplay.prototype.onToggleFullscreen = function () {
        const $body = $('body');
        const isFullscreen = $body.hasClass('kitchen-display-fullscreen')
        const isFullPage = $body.hasClass('kitchen-display-full-page')

        if (!isFullscreen && !isFullPage) {
            $body.addClass('kitchen-display-full-page');
            localStorage.setItem('kitchenDisplayFullPage', 'true');
        } else if (!isFullscreen && isFullPage) {
            $body.addClass('kitchen-display-fullscreen');
            if (document.documentElement.requestFullscreen) {
                document.documentElement.requestFullscreen();
            }
        } else if (isFullscreen && isFullPage) {
            $body.removeClass('kitchen-display-full-page');
            $body.removeClass('kitchen-display-fullscreen');
            localStorage.setItem('kitchenDisplayFullPage', 'false');
            if (document.exitFullscreen) {
                document.exitFullscreen();
            }
        }
    }

    KitchenDisplay.prototype.onWaitTimeClick = function (event) {
        let $btn = $(event.currentTarget);

        $.request('onUpdateWaitTime', {
            data: {
                itemId: $btn.data('item-id'),
                minutes: $btn.data('minutes')
            }
        });
    }

    KitchenDisplay.prototype.onExpandCardClick = function (event) {
        let $btn = $(event.currentTarget);
        let $itemsContainer = this.$el.find($btn.data('expand-target'));

        $btn.toggleClass('expanded');
        $itemsContainer.slideToggle(200);
    }

    KitchenDisplay.prototype.onRefreshButtonClick = function (event) {
        let $btn = $(event.currentTarget);

        $btn.prop('disabled', true).addClass('disabled');
        $btn.find('i').addClass('fa-spin');

        this.refreshItemsWithScrollRestore(function () {
            $btn.prop('disabled', false).removeClass('disabled');
            $btn.find('i').removeClass('fa-spin');
        });
    }

    KitchenDisplay.prototype.refreshItemsWithScrollRestore = function (callback) {
        // Store current scroll positions before DOM update
        let scrollTop = $(window).scrollTop();
        let $board = this.$el.find('[data-control="kitchen-display-board"]');
        let scrollLeft = $board.scrollLeft();
        let $list = this.$el.find('[data-control="kitchen-display-list"]');
        let listScrollTop = $list.scrollTop();
        let $container = this.$el;
        let currentView = $container.find('[data-view-container="board"]').hasClass('d-none') ? 'list' : 'board';

        $.request('onRefreshOrders').done(function () {
            // Restore scroll positions after content is rendered
            $(window).scrollTop(scrollTop);
            if (currentView === 'board') {
                $container.find('[data-control="kitchen-display-board"]').scrollLeft(scrollLeft);
            } else {
                $container.find('[data-control="kitchen-display-list"]').scrollTop(listScrollTop);
            }
            if (callback) callback();
        })
    };

    var old = $.fn.kitchenDisplay;

    $.fn.kitchenDisplay = function (option) {
        var args = Array.prototype.slice.call(arguments, 1),
            result = undefined;

        this.each(function () {
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

    $.fn.kitchenDisplay.noConflict = function () {
        $.fn.kitchenDisplay = old;
        return this;
    }

    $(document).render(function () {
        $('[data-control="kitchen-display"]').kitchenDisplay();
    })
}(window.jQuery);
