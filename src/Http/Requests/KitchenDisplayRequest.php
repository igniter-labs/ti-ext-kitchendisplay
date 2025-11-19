<?php

declare(strict_types=1);

namespace IgniterLabs\KitchenDisplay\Http\Requests;

use Igniter\System\Classes\FormRequest;
use Override;

class KitchenDisplayRequest extends FormRequest
{
    #[Override]
    public function attributes(): array
    {
        return [
            'title' => lang('igniterlabs.kitchendisplay::default.label_title'),
            'locations' => lang('igniterlabs.kitchendisplay::default.label_locations'),
            'order_statuses' => lang('igniterlabs.kitchendisplay::default.label_order_status'),
            'order_types' => lang('igniterlabs.kitchendisplay::default.label_order_types'),
            'menu_categories' => lang('igniterlabs.kitchendisplay::default.label_menu_categories'),
            'is_enabled' => lang('igniterlabs.kitchendisplay::default.label_status'),
            'orders_limit' => lang('igniterlabs.kitchendisplay::default.label_orders_limit'),
            'display_from_date' => lang('igniterlabs.kitchendisplay::default.label_display_from_date'),
            'board_columns' => lang('igniterlabs.kitchendisplay::default.label_board_columns'),
            'board_columns.*.code' => lang('igniterlabs.kitchendisplay::default.label_board_column_code'),
            'board_columns.*.label' => lang('igniterlabs.kitchendisplay::default.label_board_column_title'),
            'board_columns.*.statusId' => lang('igniterlabs.kitchendisplay::default.label_board_column_status'),
            'board_columns.*.isVisible' => lang('igniterlabs.kitchendisplay::default.label_board_column_visible'),
            'board_columns.*.priority' => lang('igniterlabs.kitchendisplay::default.label_board_column_priority'),
            'hidden_card_fields' => lang('igniterlabs.kitchendisplay::default.label_hidden_card_fields'),
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
            'board_columns' => ['array'],
            'board_columns.*.code' => ['required', 'string'],
            'board_columns.*.label' => ['required', 'string'],
            'board_columns.*.statusId' => ['nullable', 'required_if:board_columns.*.isVisible,1', 'integer'],
            'board_columns.*.isVisible' => ['required', 'boolean'],
            'board_columns.*.priority' => ['required', 'integer'],
            'hidden_card_fields' => ['nullable'],
            'hidden_card_fields.*' => ['string'],
        ];
    }
}
