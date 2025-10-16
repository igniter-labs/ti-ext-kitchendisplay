<?php

namespace IgniterLabs\KitchenDisplay\Models;

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
        'display_design' => 'array',
    ];

    protected $guarded = [];
}
