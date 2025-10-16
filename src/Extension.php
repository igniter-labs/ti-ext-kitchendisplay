<?php

namespace IgniterLabs\KitchenDisplay;

use Igniter\System\Classes\BaseExtension;
use Override;

class Extension extends BaseExtension
{
    #[Override]
    public function register(): void
    {

    }

    public function boot(): void
    {

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

    public function registerNavigation(): array
    {
        return [
            'restaurant' => [
                'child' => [
                    'kitchendisplay' => [
                        'priority' => 350,
                        'title' => lang('igniterlabs.kitchendisplay::default.text_title'),
                        'class' => 'kitchendisplay',
                        'href' => admin_url('kitchen_display'),
                        'permission' => 'IgniterLabs.KitchenDisplay.Manage'
                    ]
                ]
            ]
        ];
    }


}
