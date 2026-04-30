<?php

declare(strict_types=1);

namespace IgniterLabs\KitchenDisplay\Tests\Http\Controllers;

use Igniter\Cart\Models\Category;
use Igniter\Cart\Models\Menu;
use Igniter\Cart\Models\Order;
use Igniter\Local\Models\Location;
use IgniterLabs\KitchenDisplay\Models\KitchenDisplay;

it('loads kitchen displays list page', function(): void {
    actingAsSuperUser()
        ->get(route('igniterlabs.kitchendisplay.kitchen_displays'))
        ->assertOk();
});

it('loads create kitchen display page', function(): void {
    actingAsSuperUser()
        ->get(route('igniterlabs.kitchendisplay.kitchen_displays', ['slug' => 'create']))
        ->assertOk();
});

it('loads edit kitchen display page', function(): void {
    $kitchenDisplay = KitchenDisplay::factory()->create();

    actingAsSuperUser()
        ->get(route('igniterlabs.kitchendisplay.kitchen_displays', ['slug' => 'edit/'.$kitchenDisplay->getKey()]))
        ->assertOk();
});

it('loads kitchen display view page for enabled display', function(): void {
    $order = Order::factory()->create([
        'order_date' => now(),
        'status_id' => 1,
        'location_id' => Location::factory(),
        'order_type' => 'delivery',
    ]);
    $menu = Menu::factory()->create(['menu_price' => 10.00]);
    $menu->categories()->attach(Category::factory()->create());
    $order->menus()->createMany([
        [
            'menu_id' => $menu->getKey(),
            'name' => 'Test Menu Item 1',
            'quantity' => 2,
            'price' => 10.00,
            'subtotal' => 20.00,
        ],
        [
            'menu_id' => 2,
            'name' => 'Test Menu Item 2',
            'quantity' => 1,
            'price' => 15.00,
            'subtotal' => 15.00,
        ],
    ]);

    $kitchenDisplay = KitchenDisplay::factory()
        ->withLocations([$order->location])
        ->withOrderTypes(['delivery'])
        ->withMenuCategories([$menu->categories->first()->getKey()])
        ->enabled()
        ->create();

    actingAsSuperUser()
        ->get(route('igniterlabs.kitchendisplay.kitchen_displays', ['slug' => 'view/'.$kitchenDisplay->getKey()]))
        ->assertOk()
        ->assertSee($order->customer_name);
});

it('redirects for non-existent or disabled kitchen display view', function(): void {
    $kitchenDisplay = KitchenDisplay::factory()->disabled()->create();

    actingAsSuperUser()
        ->get(route('igniterlabs.kitchendisplay.kitchen_displays', ['slug' => 'view/'.$kitchenDisplay->getKey()]))
        ->assertRedirect();

    expect(flash()->messages()->first()->message)->toBe(lang('igniterlabs.kitchendisplay::default.alert_kitchen_display_disabled'));
});

it('creates kitchen display successfully', function(): void {
    $boardColumns = [
        [
            'code' => 'new',
            'label' => 'New Orders',
            'statusId' => 1,
            'isVisible' => true,
            'priority' => 1,
        ],
        [
            'code' => 'preparing',
            'label' => 'Preparing',
            'statusId' => 2,
            'isVisible' => true,
            'priority' => 2,
        ],
    ];

    $data = [
        'title' => 'Kitchen Display with Columns',
        'orders_limit' => 30,
        'poll_interval' => 5,
        'is_enabled' => true,
        'board_columns' => $boardColumns,
        'locations' => [],
        'order_types' => [],
        'menu_categories' => [],
    ];

    actingAsSuperUser()
        ->post(route('igniterlabs.kitchendisplay.kitchen_displays', ['slug' => 'create']), [
            'KitchenDisplay' => $data,
        ], [
            'X-Requested-With' => 'XMLHttpRequest',
            'X-IGNITER-REQUEST-HANDLER' => 'onSave',
        ]);

    $kitchenDisplay = KitchenDisplay::query()
        ->where('title', 'Kitchen Display with Columns')
        ->where('orders_limit', 30)
        ->where('is_enabled', true)
        ->first();

    expect($kitchenDisplay)->not->toBeNull()
        ->and($kitchenDisplay->board_columns)->toBeArray()
        ->and($kitchenDisplay->board_columns)->toHaveCount(2);
});

it('updates kitchen display successfully', function(): void {
    $kitchenDisplay = KitchenDisplay::factory()->create([
        'title' => 'Original Title',
        'orders_limit' => 20,
    ]);

    actingAsSuperUser()
        ->patch(route('igniterlabs.kitchendisplay.kitchen_displays', ['slug' => 'edit/'.$kitchenDisplay->getKey()]), [
            'KitchenDisplay' => [
                'title' => 'Updated Title',
                'orders_limit' => 50,
                'poll_interval' => 5,
                'is_enabled' => true,
                'locations' => [],
                'order_types' => [],
                'menu_categories' => [],
            ],
        ], [
            'X-Requested-With' => 'XMLHttpRequest',
            'X-IGNITER-REQUEST-HANDLER' => 'onSave',
        ]);

    $kitchenDisplay->refresh();

    expect($kitchenDisplay->title)->toBe('Updated Title')
        ->and($kitchenDisplay->orders_limit)->toBe(50);
});

it('deletes kitchen display successfully', function(): void {
    $kitchenDisplay = KitchenDisplay::factory()->create();

    actingAsSuperUser()
        ->post(route('igniterlabs.kitchendisplay.kitchen_displays', ['slug' => 'edit/'.$kitchenDisplay->getKey()]), [], [
            'X-Requested-With' => 'XMLHttpRequest',
            'X-IGNITER-REQUEST-HANDLER' => 'onDelete',
        ]);

    expect(KitchenDisplay::find($kitchenDisplay->getKey()))->toBeNull();
});

it('bulk deletes kitchen displays successfully', function(): void {
    $kitchenDisplays = KitchenDisplay::factory()->count(5)->create();
    $kitchenDisplayIds = $kitchenDisplays->pluck('id')->all();

    actingAsSuperUser()
        ->post(route('igniterlabs.kitchendisplay.kitchen_displays'), [
            'checked' => $kitchenDisplayIds,
        ], [
            'X-Requested-With' => 'XMLHttpRequest',
            'X-IGNITER-REQUEST-HANDLER' => 'onDelete',
        ]);

    expect(KitchenDisplay::whereIn('id', $kitchenDisplayIds)->exists())->toBeFalse();
});

