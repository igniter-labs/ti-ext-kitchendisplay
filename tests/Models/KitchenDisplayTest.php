<?php

declare(strict_types=1);

namespace IgniterLabs\KitchenDisplay\Tests\Models;

use IgniterLabs\KitchenDisplay\Models\KitchenDisplay;
use Illuminate\Support\Collection;

it('configures kitchen display model correctly', function(): void {
    $kitchenDisplay = new KitchenDisplay;

    expect($kitchenDisplay->getTable())->toBe('kitchen_displays')
        ->and($kitchenDisplay->getKeyName())->toBe('id')
        ->and($kitchenDisplay->timestamps)->toBeTrue()
        ->and($kitchenDisplay->getGuarded())->toBe([])
        ->and($kitchenDisplay->getCasts())->toHaveKeys([
            'locations',
            'menu_categories',
            'order_types',
            'board_columns',
            'hidden_card_fields',
        ]);
});

it('returns default board columns when board_columns is null', function(): void {
    $kitchenDisplay = KitchenDisplay::factory()->create(['board_columns' => null]);

    $boardColumns = $kitchenDisplay->board_columns;

    expect($boardColumns)->toBeArray()
        ->and($boardColumns)->toHaveCount(5)
        ->and($boardColumns[0])->toHaveKey('code')
        ->and($boardColumns[0])->toHaveKey('label')
        ->and($boardColumns[0])->toHaveKey('statusId')
        ->and($boardColumns[0])->toHaveKey('isVisible')
        ->and($boardColumns[0]['code'])->toBe('new')
        ->and($boardColumns[1]['code'])->toBe('preparing')
        ->and($boardColumns[2]['code'])->toBe('ready')
        ->and($boardColumns[3]['code'])->toBe('completed')
        ->and($boardColumns[4]['code'])->toBe('on-hold');
});

it('returns custom board columns when board_columns is set', function(): void {
    $customColumns = [
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

    $kitchenDisplay = KitchenDisplay::factory()->withBoardColumns($customColumns)->create();

    expect($kitchenDisplay->board_columns)->toBeArray()
        ->and($kitchenDisplay->board_columns)->toHaveCount(2)
        ->and($kitchenDisplay->board_columns[0]['code'])->toBe('new')
        ->and($kitchenDisplay->board_columns[1]['code'])->toBe('preparing');
});

it('returns only visible board columns', function(): void {
    $boardColumns = [
        [
            'code' => 'preparing',
            'label' => 'Preparing',
            'statusId' => 2,
            'isVisible' => false,
            'priority' => 2,
        ],
        [
            'code' => 'new',
            'label' => 'New Orders',
            'statusId' => 1,
            'isVisible' => true,
            'priority' => 1,
        ],
        [
            'code' => 'ready',
            'label' => 'Ready',
            'statusId' => 3,
            'isVisible' => true,
            'priority' => 3,
        ],
    ];

    $kitchenDisplay = KitchenDisplay::factory()->withBoardColumns($boardColumns)->create();

    $visibleColumns = $kitchenDisplay->getVisibleBoardColumns();

    expect($visibleColumns)->toBeInstanceOf(Collection::class)
        ->and($visibleColumns)->toHaveCount(2)
        ->and($visibleColumns->pluck('code')->all())->toEqual(['new', 'ready']);
});

it('handles null array attributes correctly', function(): void {
    $kitchenDisplay = KitchenDisplay::factory()->create([
        'locations' => null,
        'order_types' => null,
        'menu_categories' => null,
        'hidden_card_fields' => null,
    ]);

    expect($kitchenDisplay->locations)->toBeNull()
        ->and($kitchenDisplay->order_types)->toBeNull()
        ->and($kitchenDisplay->menu_categories)->toBeNull()
        ->and($kitchenDisplay->hidden_card_fields)->toBeNull();
});
