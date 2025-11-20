<?php

declare(strict_types=1);

namespace IgniterLabs\KitchenDisplay;

use Igniter\System\Classes\BaseExtension;
use IgniterLabs\KitchenDisplay\Events\KitchenDisplayUpdated;
use IgniterLabs\KitchenDisplay\Models\KitchenDisplay;
use Illuminate\Database\Eloquent\Relations\Relation;
use Override;

class Extension extends BaseExtension
{
    #[Override]
    public function boot(): void
    {
        Relation::morphMap([
            'kitchen_displays' => KitchenDisplay::class,
        ]);
    }

    #[Override]
    public function registerPermissions(): array
    {
        return [
            'IgniterLabs.KitchenDisplay.Manage' => [
                'description' => 'lang:igniterlabs.kitchendisplay::default.help_permission',
                'group' => 'igniter.cart::default.text_permission_order_group',
            ],
        ];
    }

    #[Override]
    public function registerNavigation(): array
    {
        return [
            'tools' => [
                'child' => [
                    'kitchendisplay' => [
                        'priority' => 350,
                        'title' => lang('igniterlabs.kitchendisplay::default.text_title'),
                        'class' => 'kitchendisplay',
                        'href' => admin_url('kitchendisplays'),
                        'permission' => 'IgniterLabs.KitchenDisplay.Manage',
                    ],
                ],
            ],
        ];
    }

    public function registerEventBroadcasts(): array
    {
        return [
            'igniter.cart.orderStatusAdded' => KitchenDisplayUpdated::class,
        ];
    }
}
