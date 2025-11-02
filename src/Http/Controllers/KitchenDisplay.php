<?php

namespace IgniterLabs\KitchenDisplay\Http\Controllers;

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
            'redirect' => 'kitchen_display/edit/{id}',
            'redirectClose' => 'kitchen_display',
            'redirectNew' => 'kitchen_display/create',
        ],
        'edit' => [
            'title' => 'lang:igniter::admin.form.edit_title',
            'redirect' => 'kitchen_display/edit/{id}',
            'redirectClose' => 'kitchen_display',
            'redirectNew' => 'kitchen_display/create',
        ],
        'delete' => [
            'redirect' => 'kitchen_display',
        ],
        'configFile' => 'kitchendisplay',
    ];

    protected string|array|null $requiredPermissions = 'IgniterLabs.KitchenDisplay.Manage';

    public static function getSlug(): string
    {
        return 'kitchen_display';
    }

    public function __construct()
    {
        parent::__construct();

        AdminMenu::setContext('kitchendisplay', 'restaurant');
    }

    public function view(string $context, string $kitchenDisplayId)
    {
        $kitchenDisplay = KitchenDisplayModel::find($kitchenDisplayId);

        abort_if(!$kitchenDisplay, 404);

        Template::setTitle($kitchenDisplay->title . ' | ' . lang('igniterlabs.kitchendisplay::default.text_title'));

        // add js and css assets
        $this->addJs('igniterlabs.kitchendisplay::js/vendor/Sortable.min.js', 'sortable-js');
        $this->addJs('igniterlabs.kitchendisplay::js/kitchendisplay.js', 'kitchendisplay-js');

        $this->addCss('igniterlabs.kitchendisplay::css/kitchendisplay.css', 'kitchendisplay-css');



        return $this->makeView('igniterlabs.kitchendisplay::kitchendisplay', [
            'pageTitle' => $kitchenDisplay->title,
            'boardColumns' => $this->getBoardColumns(),
        ]);
    }

    protected function getBoardColumns(): array {
        $orders = Order::where('status_id', '<>', 0)
            ->latest()->take(50)->get();
        return [
            ['status' => 'Received', 'label' => 'New', 'color' => '#f39c12',
                'orders' => $orders->where('status_name', 'Received')],
            ['status' => 'Preparation', 'label' => 'Preparing', 'color' => '#00c0ef',
                'orders' => $orders->where('status_name', 'Preparation')],
            ['status' => 'Delivery', 'label' => 'Ready to Collect', 'color' => '#00a65a',
                'orders' => $orders->where('status_name', 'Delivery')],
            ['status' => 'Completed', 'label' => 'Completed', 'color' => '#d2d6de',
                'orders' => $orders->where('status_name', 'Completed')],
            ['status' => 'Pending', 'label' => 'On Hold', 'color' => '#dd4b39',
                'orders' => $orders->where('status_name', 'Pending')],
        ];
    }

    public function onUpdateOrderStatus()
    {
        $orderId = post('order_id');
        $statusName = post('status');

        $order = Order::find($orderId);
        if (!$order)
            return;

        $status = Status::where('status_name', $statusName)->isForOrder()->first();

        if (!$status)
            return;

        $order->updateOrderStatus($status->status_id);
    }

    public function onRefreshOrders()
    {

        return $this->makePartial('igniterlabs.kitchendisplay::kitchendisplay', [
            'boardColumns' => $this->getBoardColumns(),
        ]);
    }
}
