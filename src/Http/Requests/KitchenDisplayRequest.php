<?php

namespace IgniterLabs\KitchenDisplay\Http\Requests;

use Igniter\System\Classes\FormRequest;

class KitchenDisplayRequest extends FormRequest
{
    public function attributes(): array
    {
        return [
            'title' => 'lang:igniterlabs.kitchendisplay::default.label_title',
            'locations' => 'lang:igniterlabs.kitchendisplay::default.label_locations',
            'order_statuses' => 'lang:igniterlabs.kitchendisplay::default.label_order_status',
            'order_types' => 'lang:igniterlabs.kitchendisplay::default.label_order_types',
            'menu_categories' => 'lang:igniterlabs.kitchendisplay::default.label_menu_categories',
            'is_enabled' => 'lang:igniterlabs.kitchendisplay::default.label_status',
            'orders_limit' => 'lang:igniterlabs.kitchendisplay::default.label_orders_limit',
            'display_from_date' => 'lang:igniterlabs.kitchendisplay::default.label_display_from_date',
            'column_new_statuses' => 'lang:igniterlabs.kitchendisplay::default.label_column_new',
            'column_new_visible' => 'lang:igniterlabs.kitchendisplay::default.label_column_new_visible',
            'column_preparing_statuses' => 'lang:igniterlabs.kitchendisplay::default.label_column_preparing',
            'column_preparing_visible' => 'lang:igniterlabs.kitchendisplay::default.label_column_preparing_visible',
            'column_ready_statuses' => 'lang:igniterlabs.kitchendisplay::default.label_column_ready',
            'column_ready_visible' => 'lang:igniterlabs.kitchendisplay::default.label_column_ready_visible',
            'column_completed_statuses' => 'lang:igniterlabs.kitchendisplay::default.label_column_completed',
            'column_completed_visible' => 'lang:igniterlabs.kitchendisplay::default.label_column_completed_visible',
            'column_on_hold_status' => 'lang:igniterlabs.kitchendisplay::default.label_column_on_hold',
            'column_on_hold_visible' => 'lang:igniterlabs.kitchendisplay::default.label_column_on_hold_visible',
            'hidden_card_fields' => 'lang:igniterlabs.kitchendisplay::default.label_hidden_card_fields',
        ];
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'locations' => ['nullable', 'array'],
            'order_statuses' => ['nullable', 'array'],
            'order_types' => ['nullable', 'array'],
            'menu_categories' => ['nullable', 'array'],
            'is_enabled' => ['boolean'],
            'orders_limit' => ['required', 'integer', 'min:1', 'max:100'],
            'display_from_date' => ['nullable', 'date_format:Y-m-d'],
            'column_new_statuses' => ['nullable', 'array'],
            'column_new_visible' => ['boolean'],
            'column_preparing_statuses' => ['nullable', 'array'],
            'column_preparing_visible' => ['boolean'],
            'column_ready_statuses' => ['nullable', 'array'],
            'column_ready_visible' => ['boolean'],
            'column_completed_statuses' => ['nullable', 'array'],
            'column_completed_visible' => ['boolean'],
            'column_on_hold_status' => ['required', 'string'],
            'column_on_hold_visible' => ['boolean'],
            'hidden_card_fields' => ['nullable', 'array'],
        ];
    }
}
