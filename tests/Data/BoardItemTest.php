<?php

declare(strict_types=1);

namespace IgniterLabs\KitchenDisplay\Tests\Data;

use IgniterLabs\KitchenDisplay\Data\BoardItem;
use Illuminate\Support\Collection;

it('creates board item with all properties', function(): void {
    $details = collect(['item1', 'item2']);
    $availableStatuses = [
        ['status_id' => 2, 'status_name' => 'Preparing'],
        ['status_id' => 3, 'status_name' => 'Ready'],
    ];

    $boardItem = new BoardItem(
        id: 1,
        statusId: 2,
        statusName: 'Preparing',
        customerName: 'John Doe',
        time: '12:00:00',
        formattedTime: '12:00',
        type: 'delivery',
        typeName: 'Delivery',
        details: $details,
        availableStatuses: $availableStatuses,
    );

    expect($boardItem->id)->toBe(1)
        ->and($boardItem->statusId)->toBe(2)
        ->and($boardItem->statusName)->toBe('Preparing')
        ->and($boardItem->customerName)->toBe('John Doe')
        ->and($boardItem->time)->toBe('12:00:00')
        ->and($boardItem->formattedTime)->toBe('12:00')
        ->and($boardItem->type)->toBe('delivery')
        ->and($boardItem->typeName)->toBe('Delivery')
        ->and($boardItem->details)->toBeInstanceOf(Collection::class)
        ->and($boardItem->details)->toHaveCount(2)
        ->and($boardItem->availableStatuses)->toBeArray()
        ->and($boardItem->availableStatuses)->toHaveCount(2)
        ->and($boardItem->availableStatuses[0]['status_id'])->toBe(2);

});

it('creates board item with optional properties', function(): void {
    $boardItem = new BoardItem(
        id: 1,
        statusId: 2,
        statusName: 'Preparing',
        customerName: 'John Doe',
        time: '12:00:00',
        formattedTime: '12:00',
        type: 'delivery',
        typeName: 'Delivery',
    );

    expect($boardItem->details)->toBeNull()
        ->and($boardItem->availableStatuses)->toBeArray()
        ->and($boardItem->availableStatuses)->toHaveCount(0);
});

