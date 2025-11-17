<?php

namespace IgniterLabs\KitchenDisplay\Models;

use Igniter\Admin\Models\Status;
use Igniter\Flame\Database\Model;

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
        'column_new_statuses' => 'array',
        'column_preparing_statuses' => 'array',
        'column_ready_statuses' => 'array',
        'column_completed_statuses' => 'array',
        'column_on_hold_status' => 'integer',
        'hidden_card_fields' => 'array',
    ];

    protected $guarded = [];

    public static function getOrderStatus(string|array $statusNames): array {
        if(is_string($statusNames)) {
            $statusNames = [$statusNames];
        }
        return Status::isForOrder()
            ->whereIn('status_name', $statusNames)
            ->pluck('status_id')
            ->toArray();
    }
}
