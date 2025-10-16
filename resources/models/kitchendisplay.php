<?php

use Igniter\Admin\Models\Status;
use Igniter\Cart\Models\Concerns\LocationAction;

return [
    'list' => [
        'toolbar' => [
            'buttons' => [
                'create' => [
                    'label' => 'lang:igniter::admin.button_new',
                    'class' => 'btn btn-primary',
                    'href' => 'kitchen_display/create',
                ],
            ],
        ],
        'bulkActions' => [
            'delete' => [
                'label' => 'lang:igniter::admin.button_delete',
                'class' => 'btn btn-light text-danger',
                'data-request-confirm' => 'lang:igniter::admin.alert_warning_confirm',
                'permissions' => 'IgniterLabs.KitchenDisplay.Manage',
            ],
        ],
        'columns' => [
            'edit' => [
                'type' => 'button',
                'iconCssClass' => 'fa fa-pencil',
                'attributes' => [
                    'class' => 'btn btn-edit',
                    'href' => 'kitchen_display/edit/{id}',
                ],
            ],
            'title' => [
                'label' => 'lang:igniterlabs.kitchendisplay::default.column_title',
                'type' => 'text',
                'sortable' => true,
                'formatter' => function ($record, $column, $value) {
                    return sprintf('<strong><a href="%s">%s</a></strong>', admin_url('kitchen_display/view/' . $record->id), e($value));
                },
            ],
            'is_enabled' => [
                'label' => 'lang:igniterlabs.kitchendisplay::default.column_status',
                'type' => 'switch',
                'sortable' => true,
                'formatter' => function ($record, $column, $value) {
                    return $value ? lang('igniterlabs.kitchendisplay::default.text_enabled') :
                        lang('igniterlabs.kitchendisplay::default.text_disabled');
                }
            ],
            'created_at' => [
                'label' => 'lang:igniter::admin.column_date_added',
                'type' => 'datetime',
                'sortable' => true,
            ],
        ]
    ],

    'form' => [
        'toolbar' => [
            'buttons' => [
                'save' => [
                    'label' => 'lang:igniter::admin.button_save',
                    'context' => ['create', 'edit'],
                    'partial' => 'form/toolbar_save_button',
                    'class' => 'btn btn-primary',
                    'data-request' => 'onSave',
                    'data-progress-indicator' => 'igniter::admin.text_saving',
                ],
                'delete' => [
                    'label' => 'lang:igniter::admin.button_icon_delete',
                    'class' => 'btn btn-danger',
                    'data-request' => 'onDelete',
                    'data-request-data' => "_method:'DELETE'",
                    'data-request-confirm' => 'lang:igniter::admin.alert_warning_confirm',
                    'data-progress-indicator' => 'igniter::admin.text_deleting',
                    'context' => ['edit'],
                ],
            ],
        ],
        'fields' => [
            'title' => [
                'label' => 'lang:igniterlabs.kitchendisplay::default.label_title',
                'type' => 'text',
                'span' => 'left',
            ],
            'locations' => [
                'label' => 'lang:igniterlabs.kitchendisplay::default.label_locations',
                'comment' => 'lang:igniterlabs.kitchendisplay::default.help_locations',
                'type' => 'selectlist',
                'span' => 'right',
                'options' => [\Igniter\Local\Models\Location::class, 'getDropdownOptions'],
            ],
            'order_statuses' => [
                'label' => 'lang:igniterlabs.kitchendisplay::default.label_order_status',
                'comment' => 'lang:igniterlabs.kitchendisplay::default.help_order_status',
                'type' => 'selectlist',
                'span' => 'left',
                'options' => [Status::class, 'getDropdownOptionsForOrder'],
            ],
            'order_types' => [
                'label' => 'lang:igniterlabs.kitchendisplay::default.label_order_types',
                'comment' => 'lang:igniterlabs.kitchendisplay::default.help_order_types',
                'type' => 'selectlist',
                'span' => 'right',
                'options' => [LocationAction::class, 'getOrderTypeOptions'],
            ],
            'menu_categories' => [
                'label' => 'lang:igniterlabs.kitchendisplay::default.label_menu_categories',
                'comment' => 'lang:igniterlabs.kitchendisplay::default.help_menu_categories',
                'type' => 'selectlist',
                'span' => 'left',
                'options' => [\Igniter\Cart\Models\Category::class, 'getDropdownOptions'],
            ],
            'is_enabled' => [
                'label' => 'lang:igniterlabs.kitchendisplay::default.label_status',
                'type' => 'switch',
                'default' => true,
                'span' => 'right',
            ],
            'refresh_interval' => [
                'label' => 'lang:igniterlabs.kitchendisplay::default.label_refresh_interval',
                'type' => 'number',
                'span' => 'left',
                'default' => 30,
                'attributes' => [
                    'min' => 5,
                    'step' => 5,
                ],
            ],
            'orders_limit' => [
                'label' => 'lang:igniterlabs.kitchendisplay::default.label_orders_limit',
                'comment' => 'lang:igniterlabs.kitchendisplay::default.help_orders_limit',
                'type' => 'number',
                'span' => 'right',
                'default' => 20,
                'attributes' => [
                    'min' => 1,
                    'step' => 1,
                ],
            ],
            'users_assigned_only' => [
                'label' => 'lang:igniterlabs.kitchendisplay::default.label_users_assigned_only',
                'comment' => 'lang:igniterlabs.kitchendisplay::default.help_users_assigned_only',
                'type' => 'switch',
                'span' => 'left',
                'default' => false,
            ],
            'users_assigned' => [
                'label' => 'lang:igniterlabs.kitchendisplay::default.label_users_assigned',
                'comment' => 'lang:igniterlabs.kitchendisplay::default.help_users_assigned',
                'type' => 'selectlist',
                'span' => 'right',
                'options' => [\Igniter\User\Models\User::class, 'getDropdownOptions'],
                'trigger' => [
                    'action' => 'hide',
                    'field' => 'users_assigned_only',
                    'condition' => 'checked',
                ]
            ],
        ]
    ]
];
