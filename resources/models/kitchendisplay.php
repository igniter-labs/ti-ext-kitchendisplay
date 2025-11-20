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
                    'href' => 'kitchendisplays/create',
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
                    'href' => 'kitchendisplays/edit/{id}',
                ],
            ],
            'title' => [
                'label' => 'lang:igniterlabs.kitchendisplay::default.column_title',
                'type' => 'text',
                'sortable' => true,
                'formatter' => function($record, $column, $value) {
                    return sprintf('<strong><a href="%s">%s</a></strong>',
                        admin_url('kitchendisplays/view/'.$record->id), e($value));
                },
            ],
            'is_enabled' => [
                'label' => 'lang:igniterlabs.kitchendisplay::default.column_status',
                'type' => 'switch',
                'sortable' => true,
            ],
            'created_at' => [
                'label' => 'lang:igniter::admin.column_date_added',
                'type' => 'datetime',
                'sortable' => true,
            ],
        ],
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
        'tabs' => [
            'fields' => [
                'title' => [
                    'label' => 'lang:igniterlabs.kitchendisplay::default.label_title',
                    'type' => 'text',
                    'span' => 'left',
                    'tab' => 'lang:igniterlabs.kitchendisplay::default.tab_general',
                ],
                'locations' => [
                    'label' => 'lang:igniterlabs.kitchendisplay::default.label_locations',
                    'comment' => 'lang:igniterlabs.kitchendisplay::default.help_locations',
                    'type' => 'selectlist',
                    'span' => 'right',
                    'options' => [\Igniter\Local\Models\Location::class, 'getDropdownOptions'],
                    'tab' => 'lang:igniterlabs.kitchendisplay::default.tab_general',
                ],
                'order_types' => [
                    'label' => 'lang:igniterlabs.kitchendisplay::default.label_order_types',
                    'comment' => 'lang:igniterlabs.kitchendisplay::default.help_order_types',
                    'type' => 'selectlist',
                    'span' => 'right',
                    'options' => [LocationAction::class, 'getOrderTypeOptions'],
                    'tab' => 'lang:igniterlabs.kitchendisplay::default.tab_general',
                ],
                'menu_categories' => [
                    'label' => 'lang:igniterlabs.kitchendisplay::default.label_menu_categories',
                    'comment' => 'lang:igniterlabs.kitchendisplay::default.help_menu_categories',
                    'type' => 'selectlist',
                    'span' => 'left',
                    'options' => [\Igniter\Cart\Models\Category::class, 'getDropdownOptions'],
                    'tab' => 'lang:igniterlabs.kitchendisplay::default.tab_general',
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
                    'tab' => 'lang:igniterlabs.kitchendisplay::default.tab_general',
                ],
                'is_enabled' => [
                    'label' => 'lang:igniterlabs.kitchendisplay::default.label_status',
                    'type' => 'switch',
                    'default' => true,
                    'span' => 'right',
                    'tab' => 'lang:igniterlabs.kitchendisplay::default.tab_general',
                ],

                'board_columns' => [
                    'tab' => 'lang:igniterlabs.kitchendisplay::default.tab_board_columns',
                    'label' => 'lang:igniterlabs.kitchendisplay::default.label_board_columns',
                    'comment' => 'lang:igniterlabs.kitchendisplay::default.help_board_columns',
                    'type' => 'repeater',
                    'sortable' => true,
                    'showAddButton' => false,
                    'showRemoveButton' => false,
                    'form' => [
                        'fields' => [
                            'label' => [
                                'label' => 'lang:igniterlabs.kitchendisplay::default.label_board_column_title',
                                'type' => 'text',
                                'readOnly' => true,
                            ],
                            'statusId' => [
                                'label' => 'lang:igniterlabs.kitchendisplay::default.label_board_column_status',
                                'type' => 'selectlist',
                                'mode' => 'radio',
                                'options' => [Status::class, 'getDropdownOptionsForOrder'],
                            ],
                            'isVisible' => [
                                'label' => 'lang:igniterlabs.kitchendisplay::default.label_board_column_visible',
                                'type' => 'switch',
                                'onText' => 'igniter::admin.text_yes',
                                'offText' => 'igniter::admin.text_no',
                            ],
                            'code' => [
                                'type' => 'hidden',
                            ],
                            'priority' => [
                                'type' => 'hidden',
                                'default' => 0,
                            ],
                        ],
                    ],
                ],
                'hidden_card_fields' => [
                    'tab' => 'lang:igniterlabs.kitchendisplay::default.tab_board_columns',
                    'label' => 'lang:igniterlabs.kitchendisplay::default.label_hidden_card_fields',
                    'comment' => 'lang:igniterlabs.kitchendisplay::default.help_hidden_card_fields',
                    'type' => 'checkboxlist',
                    'span' => 'left',
                    'options' => [
                        'customer_name' => 'lang:igniterlabs.kitchendisplay::default.option_customer_name',
                        'order_id' => 'lang:igniterlabs.kitchendisplay::default.option_order_id',
                        'order_type' => 'lang:igniterlabs.kitchendisplay::default.option_order_type',
                    ],
                ],
            ],
        ],
    ],
];
