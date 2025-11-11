<?php

namespace IgniterLabs\KitchenDisplay\Http\Controllers;

use Carbon\Carbon;
use Igniter\Admin\Classes\AdminController;
use Igniter\Admin\Facades\AdminMenu;
use Igniter\Admin\Facades\Template;
use Igniter\Admin\Http\Actions\FormController;
use Igniter\Admin\Http\Actions\ListController;
use Igniter\Admin\Models\Status;
use Igniter\Cart\Models\Order;
use IgniterLabs\KitchenDisplay\Http\Requests\KitchenDisplayRequest;
use IgniterLabs\KitchenDisplay\Models\KitchenDisplay as KitchenDisplayModel;

class KitchenDisplay extends AdminController
{
    public array $implement = [
        ListController::class,
        FormController::class
    ];

    public array $listConfig = [
        'list' => [
            'model' => KitchenDisplayModel::class,
            'title' => 'igniterlabs.kitchendisplay::default.text_title',
            'emptyMessage' => 'igniterlabs.kitchendisplay::default.text_empty',
            'defaultSort' => ['title', 'ASC'],
            'configFile' => 'kitchendisplay'
        ],
    ];

    public array $formConfig = [
        'name' => 'igniterlabs.kitchendisplay::default.text_form_name',
        'model' => KitchenDisplayModel::class,
        'request' => KitchenDisplayRequest::class,
        'create' => [
            'title' => 'lang:igniter::admin.form.create_title',
            'redirect' => 'igniterlabs/kitchendisplay/kitchen_display/edit/{id}',
            'redirectClose' => 'igniterlabs/kitchendisplay/kitchen_display',
            'redirectNew' => 'igniterlabs/kitchendisplay/kitchen_display/create',
        ],
        'edit' => [
            'title' => 'lang:igniter::admin.form.edit_title',
            'redirect' => 'igniterlabs/kitchendisplay/kitchen_display/edit/{id}',
            'redirectClose' => 'igniterlabs/kitchendisplay/kitchen_display',
            'redirectNew' => 'igniterlabs/kitchendisplay/kitchen_display/create',
        ],
        'delete' => [
            'redirect' => 'igniterlabs/kitchendisplay/kitchen_display',
        ],
        'configFile' => 'kitchendisplay',
    ];

    protected string|array|null $requiredPermissions = 'IgniterLabs.KitchenDisplay.Manage';


    public function __construct()
    {
        parent::__construct();

        AdminMenu::setContext('kitchendisplay', 'tools');
    }

    public function view(string $context, string $kitchenDisplayId)
    {
        $kitchenDisplay = KitchenDisplayModel::find($kitchenDisplayId);

        abort_if(!$kitchenDisplay, 404);
        abort_if(!$kitchenDisplay->is_enabled, 403);

        $this->setPageTitle($kitchenDisplay->title);
        $this->loadAssets();

        $availableStatuses = $this->getAvailableStatuses($kitchenDisplay);

        return $this->makeView('kitchendisplay', [
            'kitchenDisplayId' => $kitchenDisplay->id,
            'boardColumns' => $this->formatBoardColumnsData($this->getBoardColumns($kitchenDisplay), $availableStatuses, $kitchenDisplay->column_on_hold_status),
            'availableStatuses' => $availableStatuses,
            'hiddenCardFields' => $kitchenDisplay->hidden_card_fields ?? [],
            'onHoldStatusId' => $kitchenDisplay->column_on_hold_status,
        ]);
    }

    protected function getBoardColumns(KitchenDisplayModel $kitchenDisplay): array
    {
        $boardColumns = [];

        foreach ($this->getBoardColumnsConfig() as $column) {
            if ($this->isColumnVisible($kitchenDisplay, $column)) {
                $boardColumns[] = $this->buildBoardColumn(
                    $column,
                    $this->getFilteredOrders($kitchenDisplay),
                    $kitchenDisplay);
            }
        }

        return $boardColumns;
    }

    public function onUpdateOrderStatus(): void
    {
        $order = Order::find(post('order_id'));
        if (!$order)
            return;

        $statusId = post('status');
        if (!$statusId || !Status::isForOrder()->where('status_id', $statusId)->exists())
            return;

        $order->updateOrderStatus($statusId);
    }

    private function getAvailableStatuses(KitchenDisplayModel $kitchenDisplay): array
    {
        $query = Status::isForOrder();

        if (!empty($kitchenDisplay->order_statuses)) {
            $query->whereIn('status_id', $kitchenDisplay->order_statuses);
        }

        return $query->get()->pluck('status_name', 'status_id')->toArray();
    }

    public function onUpdateWaitTime(): void
    {
        $order = Order::find(post('order_id'));
        if (!$order)
            return;

        $currentTime = Carbon::createFromFormat('H:i:s', $order->order_time);
        $newTime = post('custom_time')
            ? Carbon::createFromFormat('H:i', post('custom_time'))
            : $currentTime->addMinutes((int) post('minutes'));

        $order->order_time = $newTime->format('H:i:s');
        $order->save();
    }

    private function formatBoardColumnsData(array $boardColumns, array $availableStatuses, int|null $onHoldStatusId = null): array
    {
        foreach ($boardColumns as $column) {
            foreach ($column['orders'] as $order) {
                $this->formatOrderData($order, $availableStatuses, $onHoldStatusId);
            }
        }

        return $boardColumns;
    }

    private function formatOrderData($order, array $availableStatuses, int|null $onHoldStatusId = null): void
    {
        if ($order->order_date && $order->order_time) {
            $orderDateTime = $order->order_date->setTimeFromTimeString($order->order_time);
            $order->formatted_time = $orderDateTime->format('H:i');
        }

        $order->order_type_icon = $order->order_type === 'delivery' ? 'truck' : 'shopping-bag';
        $order->order_type_display = ucfirst($order->order_type);
        $order->filtered_statuses = collect($availableStatuses)
            ->filter(function ($statusName, $statusId) use ($onHoldStatusId, $order) {
                // Exclude on-hold status and current status from dropdown
                return $statusId !== $onHoldStatusId && $statusId !== $order->status_id;
            })
            ->toArray();
    }

    public function onRefreshOrders(): string
    {
        $kitchenDisplay = KitchenDisplayModel::find(post('kitchenDisplayId'));

        $availableStatuses = $this->getAvailableStatuses($kitchenDisplay);

        return $this->makePartial('igniterlabs.kitchendisplay::partials.kitchendisplay-board', [
            'boardColumns' => $this->formatBoardColumnsData($this->getBoardColumns($kitchenDisplay), $availableStatuses, $kitchenDisplay->column_on_hold_status),
            'availableStatuses' => $availableStatuses,
            'hiddenCardFields' => $kitchenDisplay->hidden_card_fields ?? [],
            'onHoldStatusId' => $kitchenDisplay->column_on_hold_status,
        ]);
    }

    private function setPageTitle(string $title): void
    {
        $pageTitle = $title . ' | ' . lang('igniterlabs.kitchendisplay::default.text_title');
        Template::setTitle($pageTitle);
        Template::setHeading($pageTitle);
    }

    private function loadAssets(): void
    {
        $this->addJs('igniterlabs.kitchendisplay::js/kitchendisplay.js', 'kitchendisplay-js');
        $this->addCss('igniterlabs.kitchendisplay::css/kitchendisplay.css', 'kitchendisplay-css');
    }

    private function getFilteredOrders(KitchenDisplayModel $kitchenDisplay): mixed
    {
        $query = Order::where('status_id', '<>', 0)
            ->whereDoesntHave('status', function ($q) {
                $q->where('status_name', lang('igniterlabs.kitchendisplay::default.status_rejected'));
            });

        if (!empty($kitchenDisplay->order_statuses)) {
            $query->whereIn('status_id', $kitchenDisplay->order_statuses);
        }

        if (!empty($kitchenDisplay->locations)) {
            $query->whereIn('location_id', $kitchenDisplay->locations);
        }

        if (!empty($kitchenDisplay->order_types)) {
            $query->whereIn('order_type', $kitchenDisplay->order_types);
        }

        if (!empty($kitchenDisplay->menu_categories)) {
            $query->whereHas('menus.menu.categories', function ($q) use ($kitchenDisplay) {
                $q->whereIn('category_id', $kitchenDisplay->menu_categories);
            });
        }

        $query->whereDate('order_date', '>=', $kitchenDisplay->display_from_date ?? today());

        return $query->latest()->take($kitchenDisplay->orders_limit ?? 20)->get();
    }

    private function getBoardColumnsConfig(): array
    {
        return [
            [
                'label' => lang('igniterlabs.kitchendisplay::default.board_column_new'),
                'color' => '#f39c12',
                'statuses_key' => 'column_new_statuses',
                'visible_key' => 'column_new_visible',
            ],
            [
                'label' => lang('igniterlabs.kitchendisplay::default.board_column_preparing'),
                'color' => '#00c0ef',
                'statuses_key' => 'column_preparing_statuses',
                'visible_key' => 'column_preparing_visible',
            ],
            [
                'label' => lang('igniterlabs.kitchendisplay::default.board_column_ready'),
                'color' => '#00a65a',
                'statuses_key' => 'column_ready_statuses',
                'visible_key' => 'column_ready_visible',
            ],
            [
                'label' => lang('igniterlabs.kitchendisplay::default.board_column_completed'),
                'color' => '#d2d6de',
                'statuses_key' => 'column_completed_statuses',
                'visible_key' => 'column_completed_visible',
            ],
            [
                'label' => lang('igniterlabs.kitchendisplay::default.board_column_on_hold'),
                'color' => '#dd4b39',
                'status_key' => 'column_on_hold_status',
                'visible_key' => 'column_on_hold_visible',
            ],
        ];
    }

    private function isColumnVisible(KitchenDisplayModel $kitchenDisplay, array $column): bool
    {
        return $kitchenDisplay->{$column['visible_key']} ?? true;
    }

    private function buildBoardColumn(array $column, mixed $orders, KitchenDisplayModel $kitchenDisplay): array
    {
        // Handle both array statuses (for regular columns) and single status (for on-hold)
        $key = $column['status_key'] ?? $column['statuses_key'];
        $statusValue = $kitchenDisplay->{$key};
        $statusIds = is_array($statusValue) ? $statusValue : ($statusValue ? [$statusValue] : []);

        $columnOrders = $orders->filter(fn ($order) => in_array($order->status_id, $statusIds));

        return [
            'label' => $column['label'],
            'color' => $column['color'],
            'orders' => $columnOrders,
            'status_id' => $statusIds[0] ?? null,
        ];
    }
}
