<?php

declare(strict_types=1);

namespace IgniterLabs\KitchenDisplay\Tests\Widgets;

use Igniter\Admin\Models\Status;
use Igniter\Cart\Models\Order;
use Igniter\Flame\Exception\FlashException;
use IgniterLabs\KitchenDisplay\Data\BoardItem;
use IgniterLabs\KitchenDisplay\Http\Controllers\KitchenDisplays;
use IgniterLabs\KitchenDisplay\Models\KitchenDisplay as KitchenDisplayModel;
use IgniterLabs\KitchenDisplay\Widgets\KitchenDisplay;

it('initializes widget with model and wait times', function(): void {
    $model = KitchenDisplayModel::factory()->create();
    $waitTimes = [5, 10, 15, 20, 30];

    $widget = new KitchenDisplay(resolve(KitchenDisplays::class), [
        'model' => $model,
        'waitTimes' => $waitTimes,
    ]);

    $widget->initialize();

    expect($widget->model)->toBe($model)
        ->and($widget->waitTimes)->toBe($waitTimes);
});

it('prepares vars with board columns and items', function(): void {
    $status = Status::factory()->create(['status_for' => 'order']);
    $model = KitchenDisplayModel::factory()
        ->withBoardColumns([
            [
                'code' => 'new',
                'label' => 'New',
                'statusId' => $status->getKey(),
                'isVisible' => true,
                'priority' => 1,
            ],
        ])
        ->create();

    $widget = new KitchenDisplay(resolve(KitchenDisplays::class), ['model' => $model]);
    $widget->initialize();

    $widget->bindEvent('kitchendisplay.getBoardItems', fn(): array => [
        new BoardItem(
            id: 1,
            statusId: $status->getKey(),
            statusName: 'New',
            customerName: 'John Doe',
            time: '12:00:00',
            formattedTime: '12:00',
            type: 'delivery',
            typeName: 'Delivery',
        ),
    ]);

    $widget->prepareVars();

    expect($widget->vars)->toHaveKey('boardColumns')
        ->and($widget->vars)->toHaveKey('boardItems')
        ->and($widget->vars)->toHaveKey('onHoldStatusId')
        ->and($widget->vars)->toHaveKey('viewMode')
        ->and($widget->vars['boardColumns'])->toBeCollection()
        ->and($widget->vars['boardItems'])->toBeArray();
});

it('filters column items by status id', function(): void {
    $status1 = Status::factory()->create(['status_for' => 'order']);
    $status2 = Status::factory()->create(['status_for' => 'order']);

    $model = KitchenDisplayModel::factory()
        ->withBoardColumns([
            [
                'code' => 'new',
                'label' => 'New',
                'statusId' => $status1->getKey(),
                'isVisible' => true,
                'priority' => 1,
            ],
            [
                'code' => 'preparing',
                'label' => 'Preparing',
                'statusId' => $status2->getKey(),
                'isVisible' => true,
                'priority' => 2,
            ],
        ])
        ->create();

    $widget = new KitchenDisplay(resolve(KitchenDisplays::class), ['model' => $model]);
    $widget->initialize();

    $widget->vars['boardItems'] = [
        new BoardItem(
            id: 1,
            statusId: $status1->getKey(),
            statusName: 'New',
            customerName: 'John Doe',
            time: '12:00:00',
            formattedTime: '12:00',
            type: 'delivery',
            typeName: 'Delivery',
        ),
        new BoardItem(
            id: 2,
            statusId: $status2->getKey(),
            statusName: 'Preparing',
            customerName: 'Jane Doe',
            time: '12:30:00',
            formattedTime: '12:30',
            type: 'collection',
            typeName: 'Collection',
        ),
    ];

    $boardColumn = (object)['statusId' => $status1->getKey()];
    $columnItems = $widget->getColumnItems($boardColumn);

    expect($columnItems)->toBeArray()
        ->and($columnItems)->toHaveCount(1)
        ->and($columnItems[0]->id)->toBe(1)
        ->and($columnItems[0]->statusId)->toBe($status1->getKey());
});

it('checks if card field is hidden', function(): void {
    $model = KitchenDisplayModel::factory()->create([
        'hidden_card_fields' => ['customer_name', 'order_time'],
    ]);

    $widget = new KitchenDisplay(resolve(KitchenDisplays::class), ['model' => $model]);
    $widget->initialize();

    expect($widget->isHiddenCardField('customer_name'))->toBeTrue()
        ->and($widget->isHiddenCardField('order_time'))->toBeTrue()
        ->and($widget->isHiddenCardField('order_type'))->toBeFalse();
});

it('returns card statuses for item', function(): void {
    $status1 = Status::factory()->create(['status_for' => 'order', 'status_name' => 'New']);
    $status2 = Status::factory()->create(['status_for' => 'order', 'status_name' => 'Preparing']);
    $status3 = Status::factory()->create(['status_for' => 'order', 'status_name' => 'Ready']);
    $onHoldStatus = Status::factory()->create(['status_for' => 'order', 'status_name' => 'On Hold']);

    $model = KitchenDisplayModel::factory()
        ->withBoardColumns([
            [
                'code' => 'new',
                'label' => 'New',
                'statusId' => $status1->getKey(),
                'isVisible' => true,
                'priority' => 1,
            ],
            [
                'code' => 'preparing',
                'label' => 'Preparing',
                'statusId' => $status2->getKey(),
                'isVisible' => true,
                'priority' => 2,
            ],
            [
                'code' => 'ready',
                'label' => 'Ready',
                'statusId' => $status3->getKey(),
                'isVisible' => true,
                'priority' => 3,
            ],
            [
                'code' => 'on-hold',
                'label' => 'On Hold',
                'statusId' => $onHoldStatus->getKey(),
                'isVisible' => true,
                'priority' => 2,
            ],
        ])
        ->create();

    $widget = new KitchenDisplay(resolve(KitchenDisplays::class), ['model' => $model]);
    $widget->initialize();
    $widget->prepareVars();

    $cardStatuses = $widget->getCardStatuses($status1->getKey());

    expect($cardStatuses)->toBeArray()
        ->and($cardStatuses)->toHaveKey($status2->getKey())
        ->and($cardStatuses)->toHaveKey($status3->getKey())
        ->and($cardStatuses)->not->toHaveKey($status1->getKey())
        ->and($cardStatuses)->not->toHaveKey($onHoldStatus->getKey())
        ->and($cardStatuses[$status2->getKey()])->toBe('Preparing')
        ->and($cardStatuses[$status3->getKey()])->toBe('Ready');
});

it('returns board column color from status', function(): void {
    $status = Status::factory()->create([
        'status_for' => 'order',
        'status_color' => '#ff0000',
    ]);

    $model = KitchenDisplayModel::factory()
        ->withBoardColumns([
            [
                'code' => 'new',
                'label' => 'New',
                'statusId' => $status->getKey(),
                'isVisible' => true,
                'priority' => 1,
            ],
        ])
        ->create();

    $widget = new KitchenDisplay(resolve(KitchenDisplays::class), ['model' => $model]);
    $widget->initialize();
    $widget->prepareVars();

    $color = $widget->getBoardColumnColor($status->getKey());

    expect($color)->toBe('#ff0000');
});

it('returns default color for board column with no status', function(): void {
    $model = KitchenDisplayModel::factory()
        ->withBoardColumns([
            [
                'code' => 'ready',
                'label' => 'Ready',
                'statusId' => null,
                'isVisible' => false,
                'priority' => 1,
            ],
        ])
        ->create();

    $widget = new KitchenDisplay(resolve(KitchenDisplays::class), ['model' => $model]);
    $widget->initialize();
    $widget->prepareVars();

    $color = $widget->getBoardColumnColor(99999);

    expect($color)->toBe('#d2d6de');
});

it('toggles view mode between board and list', function(): void {
    $model = KitchenDisplayModel::factory()->create();

    $widget = new KitchenDisplay(resolve(KitchenDisplays::class), ['model' => $model]);
    $widget->initialize();

    $widget->putSession('kitchendisplay.viewMode', 'board');

    $result = $widget->onViewToggle();

    expect($widget->getSession('kitchendisplay.viewMode'))->toBe('list')
        ->and($result)->toBeArray()
        ->and($result)->toHaveKey('[data-control="kitchen-display"]');
});

it('updates order status successfully', function(): void {
    $status1 = Status::factory()->create(['status_for' => 'order']);
    $status2 = Status::factory()->create(['status_for' => 'order']);

    $model = KitchenDisplayModel::factory()
        ->withBoardColumns([
            [
                'code' => 'new',
                'label' => 'New',
                'statusId' => $status1->getKey(),
                'isVisible' => true,
                'priority' => 1,
            ],
            [
                'code' => 'preparing',
                'label' => 'Preparing',
                'statusId' => $status2->getKey(),
                'isVisible' => true,
                'priority' => 2,
            ],
        ])
        ->create();

    $order = Order::factory()->create([
        'status_id' => $status1->getKey(),
        'order_date' => today(),
    ]);

    $widget = new KitchenDisplay(resolve(KitchenDisplays::class), ['model' => $model]);
    $widget->initialize();

    $widget->putSession('kitchendisplay.viewMode', 'board');

    request()->request->add(['itemId' => $order->getKey(), 'statusId' => $status2->getKey()]);

    $result = $widget->onUpdateOrderStatus();

    $order->refresh();

    expect($order->status_id)->toBe($status2->getKey())
        ->and($result)->toBeArray()
        ->and($result)->toHaveKey('[data-control="kitchen-display"]');
});

it('throws exception when order not found for status update', function(): void {
    $model = KitchenDisplayModel::factory()->create();

    $widget = new KitchenDisplay(resolve(KitchenDisplays::class), ['model' => $model]);
    $widget->initialize();

    request()->request->add(['itemId' => 99999, 'statusId' => 1]);

    expect(fn(): array => $widget->onUpdateOrderStatus())
        ->toThrow(FlashException::class);
});

it('throws exception when status not found for status update', function(): void {
    $order = Order::factory()->create();
    $model = KitchenDisplayModel::factory()->create();

    $widget = new KitchenDisplay(resolve(KitchenDisplays::class), ['model' => $model]);
    $widget->initialize();

    request()->request->add(['itemId' => $order->getKey(), 'statusId' => 99999]);

    expect(fn(): array => $widget->onUpdateOrderStatus())
        ->toThrow(FlashException::class);
});

it('updates wait time with minutes', function(): void {
    $model = KitchenDisplayModel::factory()->create();
    $order = Order::factory()->create([
        'order_time' => '12:00:00',
        'order_date' => today(),
    ]);

    $widget = new KitchenDisplay(resolve(KitchenDisplays::class), ['model' => $model]);
    $widget->initialize();

    $widget->putSession('kitchendisplay.viewMode', 'board');

    request()->request->add(['itemId' => $order->getKey(), 'minutes' => 15]);

    $result = $widget->onUpdateWaitTime();

    $order->refresh();

    expect($order->order_time)->toBe('12:15:00')
        ->and($result)->toBeArray()
        ->and($result)->toHaveKey('[data-control="kitchen-display"]');
});

it('updates wait time with custom time', function(): void {
    $model = KitchenDisplayModel::factory()->create();
    $order = Order::factory()->create([
        'order_time' => '12:00:00',
        'order_date' => today(),
    ]);

    $widget = new KitchenDisplay(resolve(KitchenDisplays::class), ['model' => $model]);
    $widget->initialize();

    $widget->putSession('kitchendisplay.viewMode', 'board');

    request()->request->add(['itemId' => $order->getKey(), 'customTime' => '13:30']);

    $result = $widget->onUpdateWaitTime();

    $order->refresh();

    expect($order->order_time)->toBe('13:30:00')
        ->and($result)->toBeArray();
});

it('throws exception when order not found for wait time update', function(): void {
    $model = KitchenDisplayModel::factory()->create();

    $widget = new KitchenDisplay(resolve(KitchenDisplays::class), ['model' => $model]);
    $widget->initialize();

    request()->request->add(['itemId' => 99999, 'minutes' => 15]);

    expect(fn(): array => $widget->onUpdateWaitTime())
        ->toThrow(FlashException::class);
});

it('refreshes orders and returns view', function(): void {
    $model = KitchenDisplayModel::factory()->create();

    $widget = new KitchenDisplay(resolve(KitchenDisplays::class), ['model' => $model]);
    $widget->initialize();

    $widget->putSession('kitchendisplay.viewMode', 'board');

    $widget->bindEvent('kitchendisplay.getBoardItems', fn(): array => []);

    $result = $widget->onRefreshOrders();

    expect($result)->toBeArray()
        ->and($result)->toHaveKey('[data-control="kitchen-display"]');
});
