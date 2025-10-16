<?php

namespace IgniterLabs\KitchenDisplay\Http\Controllers;

use Igniter\Admin\Classes\AdminController;
use Igniter\Admin\Facades\AdminMenu;
use Igniter\Admin\Http\Actions\FormController;
use Igniter\Admin\Http\Actions\ListController;
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
        ]
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
}
