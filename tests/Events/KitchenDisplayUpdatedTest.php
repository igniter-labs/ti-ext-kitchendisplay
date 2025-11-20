<?php

declare(strict_types=1);

namespace IgniterLabs\KitchenDisplay\Tests\Events;

use IgniterLabs\KitchenDisplay\Events\KitchenDisplayUpdated;
use Illuminate\Broadcasting\Channel;

it('creates event with order id and new time', function(): void {
    $event = new KitchenDisplayUpdated(123, '13:30');

    expect($event->orderId)->toBe(123)
        ->and($event->newTime)->toBe('13:30');
});

it('broadcasts on correct channel, event name and payload', function(): void {
    $event = new KitchenDisplayUpdated(123, '13:30');

    $channels = $event->broadcastOn();
    $payload = $event->broadcastWith();

    expect($channels)->toBeArray()
        ->and($channels)->toHaveCount(1)
        ->and($channels[0])->toBeInstanceOf(Channel::class)
        ->and($channels[0]->name)->toBe('igniterlabs.kitchendisplay')
        ->and($event->broadcastAs())->toBe('kitchendisplay.updated')
        ->and($payload)->toBeArray()
        ->and($payload)->toHaveKey('order_id')
        ->and($payload)->toHaveKey('new_time')
        ->and($payload['order_id'])->toBe(123)
        ->and($payload['new_time'])->toBe('13:30');
});

