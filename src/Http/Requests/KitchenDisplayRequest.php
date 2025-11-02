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
            'refresh_interval' => 'lang:igniterlabs.kitchendisplay::default.label_refresh_interval',
            'orders_limit' => 'lang:igniterlabs.kitchendisplay::default.label_orders_limit',
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
            'refresh_interval' => ['required', 'integer', 'min:5'],
            'orders_limit' => ['required', 'integer', 'min:1', 'max:100'],
        ];
    }
}
