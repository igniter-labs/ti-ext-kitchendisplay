<?php

declare(strict_types=1);

namespace IgniterLabs\KitchenDisplay\Tests;

use IgniterLabs\KitchenDisplay\Extension;

it('registers permissions correctly', function(): void {
    $extension = new Extension(app());

    $permissions = $extension->registerPermissions();

    expect($permissions)->toBeArray()
        ->and($permissions)->toHaveKey('IgniterLabs.KitchenDisplay.Manage')
        ->and($permissions['IgniterLabs.KitchenDisplay.Manage'])->toBeArray()
        ->and($permissions['IgniterLabs.KitchenDisplay.Manage'])->toHaveKey('description')
        ->and($permissions['IgniterLabs.KitchenDisplay.Manage'])->toHaveKey('group')
        ->and($permissions['IgniterLabs.KitchenDisplay.Manage']['group'])->toBe('igniter.cart::default.text_permission_order_group');
});

it('registers navigation correctly', function(): void {
    $extension = new Extension(app());

    $navigation = $extension->registerNavigation();

    expect($navigation)->toBeArray()
        ->and($navigation)->toHaveKey('tools')
        ->and($navigation['tools'])->toHaveKey('child')
        ->and($navigation['tools']['child'])->toHaveKey('kitchendisplay')
        ->and($navigation['tools']['child']['kitchendisplay'])->toBeArray()
        ->and($navigation['tools']['child']['kitchendisplay'])->toHaveKey('priority')
        ->and($navigation['tools']['child']['kitchendisplay'])->toHaveKey('title')
        ->and($navigation['tools']['child']['kitchendisplay'])->toHaveKey('class')
        ->and($navigation['tools']['child']['kitchendisplay'])->toHaveKey('href')
        ->and($navigation['tools']['child']['kitchendisplay'])->toHaveKey('permission')
        ->and($navigation['tools']['child']['kitchendisplay']['priority'])->toBe(350)
        ->and($navigation['tools']['child']['kitchendisplay']['class'])->toBe('kitchendisplay')
        ->and($navigation['tools']['child']['kitchendisplay']['permission'])->toBe('IgniterLabs.KitchenDisplay.Manage');
});

