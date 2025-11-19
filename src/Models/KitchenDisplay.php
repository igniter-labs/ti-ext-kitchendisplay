<?php

declare(strict_types=1);

namespace IgniterLabs\KitchenDisplay\Models;

use Igniter\Flame\Database\Model;
use Illuminate\Support\Collection;

class KitchenDisplay extends Model
{
    protected $table = 'kitchen_displays';

    public $timestamps = true;

    public $casts = [
        'locations' => 'array',
        'menu_categories' => 'array',
        'order_statuses' => 'array',
        'order_types' => 'array',
        'users_assigned' => 'array',
        'board_columns' => 'array',
        'column_new_statuses' => 'array',
        'column_preparing_statuses' => 'array',
        'column_ready_statuses' => 'array',
        'column_completed_statuses' => 'array',
        'column_on_hold_status' => 'integer',
        'hidden_card_fields' => 'array',
    ];

    protected $guarded = [];

    public function getBoardColumnsAttribute($value)
    {
        return json_decode((string)$value ?: '', true) ?: [
            [
                'code' => 'new',
                'label' => lang('igniterlabs.kitchendisplay::default.text_board_column_new'),
                'statusId' => setting('default_order_status'),
                'isVisible' => true,
            ],
            [
                'code' => 'preparing',
                'label' => lang('igniterlabs.kitchendisplay::default.text_board_column_preparing'),
                'statusId' => setting('processing_order_status')[0] ?? null,
                'isVisible' => true,
            ],
            [
                'code' => 'ready',
                'label' => lang('igniterlabs.kitchendisplay::default.text_board_column_ready'),
                'statusId' => null,
                'isVisible' => true,
            ],
            [
                'code' => 'completed',
                'label' => lang('igniterlabs.kitchendisplay::default.text_board_column_completed'),
                'statusId' => setting('completed_order_status')[0] ?? null,
                'isVisible' => true,
            ],
            [
                'code' => 'on-hold',
                'label' => lang('igniterlabs.kitchendisplay::default.text_board_column_on_hold'),
                'statusId' => null,
                'isVisible' => true,
            ],
        ];
    }

    public function getVisibleBoardColumns(): Collection
    {
        return collect($this->board_columns ?: [])->filter(fn(array $column) => array_get($column, 'isVisible', false))->sortBy('priority');
    }
}
