<?php

declare(strict_types=1);

namespace IgniterLabs\KitchenDisplay\Http\Controllers;

use Igniter\Admin\Classes\AdminController;
use Igniter\Admin\Facades\AdminMenu;
use Igniter\Admin\Facades\Template;
use Igniter\Admin\Http\Actions\FormController;
use Igniter\Admin\Http\Actions\ListController;
use Igniter\Cart\Models\Order;
use Igniter\Local\Http\Actions\LocationAwareController;
use Igniter\User\Http\Actions\AssigneeController;
use IgniterLabs\KitchenDisplay\Data\BoardItem;
use IgniterLabs\KitchenDisplay\Http\Requests\KitchenDisplayRequest;
use IgniterLabs\KitchenDisplay\Models\KitchenDisplay as KitchenDisplayModel;
use IgniterLabs\KitchenDisplay\Widgets\KitchenDisplay;
use Illuminate\Http\RedirectResponse;

class KitchenDisplays extends AdminController
{
    public array $implement = [
        ListController::class,
        FormController::class,
        LocationAwareController::class,
        AssigneeController::class,
    ];

    public array $listConfig = [
        'list' => [
            'model' => KitchenDisplayModel::class,
            'title' => 'igniterlabs.kitchendisplay::default.text_title',
            'emptyMessage' => 'igniterlabs.kitchendisplay::default.text_empty',
            'defaultSort' => ['title', 'ASC'],
            'configFile' => 'kitchendisplay',
        ],
    ];

    public array $formConfig = [
        'name' => 'igniterlabs.kitchendisplay::default.text_form_name',
        'model' => KitchenDisplayModel::class,
        'request' => KitchenDisplayRequest::class,
        'create' => [
            'title' => 'lang:igniter::admin.form.create_title',
            'redirect' => 'kitchendisplays/edit/{id}',
            'redirectClose' => 'kitchendisplays',
            'redirectNew' => 'kitchendisplays/create',
        ],
        'edit' => [
            'title' => 'lang:igniter::admin.form.edit_title',
            'redirect' => 'kitchendisplays/edit/{id}',
            'redirectClose' => 'kitchendisplays',
            'redirectNew' => 'kitchendisplays/create',
        ],
        'delete' => [
            'redirect' => 'kitchendisplays',
        ],
        'configFile' => 'kitchendisplay',
    ];

    public ?KitchenDisplay $kitchenDisplayWidget = null;

    public array $kitchenDisplayConfig = [];

    public array $requiredKitchenDisplayConfig = [];

    protected string|array|null $requiredPermissions = 'IgniterLabs.KitchenDisplay.Manage';

    public static function getSlug(): string
    {
        return 'kitchendisplays';
    }

    public function __construct()
    {
        parent::__construct();

        AdminMenu::setContext('kitchendisplay', 'tools');
    }

    public function edit(string $context, string $recordId): void
    {
        Template::setButton('<i class="fa fa-eye"></i>', [
            'class' => 'btn btn-default',
            'href' => admin_url('kitchendisplays/view/'.$recordId),
        ]);

        $this->asExtension('FormController')->edit($context, $recordId);
    }

    public function view(string $context, string $recordId): RedirectResponse|string
    {
        $model = $this->asExtension('FormController')->formFindModelObject($recordId);

        if (!$model || !$model->is_enabled) {
            flash()->error(lang('igniterlabs.kitchendisplay::default.alert_kitchen_display_disabled'));

            return $this->redirect('kitchendisplays');
        }

        $pageTitle = $model->title.' | '.lang('igniterlabs.kitchendisplay::default.text_title');
        Template::setTitle($pageTitle);
        Template::setHeading($pageTitle);
        AdminMenu::setPreviousUrl('kitchendisplays');

        $this->kitchenDisplayWidget = $this->makeKitchenDisplay($model);

        return $this->makeView('kitchendisplay/view', ['model' => $model]);
    }

    protected function makeKitchenDisplay(KitchenDisplayModel $model): KitchenDisplay
    {
        $alias ??= $this->primaryAlias;

        $widgetConfig = [];
        $widgetConfig['alias'] = $alias;
        $widgetConfig['model'] = $model;
        $widgetConfig['waitTimes'] = [5, 10, 15, 20, 30];

        /** @var KitchenDisplay $widget */
        $widget = $this->makeWidget(KitchenDisplay::class, $widgetConfig);

        $widget->bindEvent('kitchendisplay.getBoardItems', $this->getFilteredOrders(...));

        $widget->bindToController();

        return $widget;
    }

    public function renderKitchenDisplay(): string
    {
        return $this->kitchenDisplayWidget->render();
    }

    protected function getFilteredOrders(KitchenDisplayModel $model): array
    {
        $visibleStatusIds = $model->getVisibleBoardColumns()->pluck('statusId')->filter()->all();

        $query = Order::query()->where('status_id', '<>', 0);

        $query->whereIn('status_id', $visibleStatusIds);

        if ($model->locations->isNotEmpty()) {
            $query->whereIn('location_id', $model->locations->pluck('location_id')->all());
        }

        if (!empty($model->order_types)) {
            $query->whereIn('order_type', $model->order_types);
        }

        if (!empty($model->menu_categories)) {
            $query->whereHas('menus.menu.categories', function($q) use ($model): void {
                $q->whereIn('menu_categories.category_id', $model->menu_categories);
            });
        }

        $query->whereDate('order_date', today());

        return $query
            ->latest()
            ->take($model->orders_limit ?? 20)
            ->get()
            ->map(fn(Order $order): BoardItem => new BoardItem(
                id: $order->order_id,
                statusId: $order->status_id,
                statusName: $order->status->status_name,
                customerName: $order->customer_name,
                time: $order->order_time,
                formattedTime: $order->order_date->setTimeFromTimeString($order->order_time)->format('H:i'),
                type: $order->order_type,
                typeName: $order->order_type_name,
                details: $order->getOrderMenusWithOptions(),
            ))
            ->toArray();
    }
}
