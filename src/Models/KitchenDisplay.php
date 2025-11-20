<?php

declare(strict_types=1);

namespace IgniterLabs\KitchenDisplay\Models;

use Igniter\Flame\Database\Factories\HasFactory;
use Igniter\Flame\Database\Model;
use Illuminate\Support\Collection;

class KitchenDisplay extends Model
{
    use HasFactory;

    protected $table = 'kitchen_displays';

    public $timestamps = true;

    public $casts = [
        'locations' => 'array',
        'menu_categories' => 'array',
        'order_types' => 'array',
        'board_columns' => 'array',
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
