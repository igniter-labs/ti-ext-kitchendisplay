<?php

declare(strict_types=1);

namespace IgniterLabs\KitchenDisplay\Widgets;

use Carbon\Carbon;
use Igniter\Admin\Classes\BaseWidget;
use Igniter\Admin\Models\Status;
use Igniter\Admin\Traits\ValidatesForm;
use Igniter\Cart\Models\Order;
use Igniter\Flame\Exception\FlashException;
use IgniterLabs\KitchenDisplay\Data\BoardItem;
use IgniterLabs\KitchenDisplay\Models\KitchenDisplay as KitchenDisplayModel;
use Illuminate\Support\Collection;
use Override;
use stdClass;

class KitchenDisplay extends BaseWidget
{
    use ValidatesForm;

    public ?KitchenDisplayModel $model = null;

    public array $waitTimes = [];

    #[Override]
    public function initialize(): void
    {
        $this->fillFromConfig([
            'model',
            'waitTimes',
        ]);
    }

    #[Override]
    public function loadAssets(): void
    {
        $this->addJs('igniterlabs.kitchendisplay::js/kitchendisplay.js', 'kitchendisplay-js');
        $this->addCss('igniterlabs.kitchendisplay::css/kitchendisplay.css', 'kitchendisplay-css');
    }

    #[Override]
    public function render(): string
    {
        $this->prepareVars();

        return $this->makePartial('kitchendisplay/kitchendisplay');
    }

    public function prepareVars(): void
    {
        $this->vars['boardColumns'] = $this->getBoardColumns();
        $this->vars['boardItems'] = $this->getBoardItems();
        $this->vars['onHoldStatusId'] = $this->model->getVisibleBoardColumns()->firstWhere('code', 'on-hold')['statusId'] ?? null;
        $this->vars['viewMode'] = $this->getSession('kitchendisplay.viewMode', 'board');
    }

    public function getColumnItems(object $boardColumn): array
    {
        return collect($this->vars['boardItems'])
            ->filter(fn(BoardItem $item): bool => $item->statusId === (int)$boardColumn->statusId)
            ->all();
    }

    public function isHiddenCardField(string $field): bool
    {
        $hiddenFields = $this->model->hidden_card_fields ?: [];

        return in_array($field, $hiddenFields);
    }

    public function getCardStatuses(int $itemStatusId): array
    {
        return $this->vars['boardColumns']
            ->filter(fn(object $boardColumn): bool => $boardColumn->code !== 'on-hold' && ((int)$boardColumn->statusId) !== $itemStatusId)
            ->pluck('statusName', 'statusId')
            ->filter()
            ->all();
    }

    public function getBoardColumnColor(int $itemStatusId): string
    {
        $status = $this->vars['boardColumns']->firstWhere('statusId', $itemStatusId);

        return $status->statusColor ?? '#d2d6de';
    }

    public function getNextStatusId(int $itemStatusId): int
    {
        $boardColumn = $this->vars['boardColumns']->firstWhere('statusId', $itemStatusId);

        return $boardColumn?->nextStatusId ?: 0;
    }

    public function onViewToggle(): array
    {
        $currentMode = $this->getSession('kitchendisplay.viewMode', 'board');
        $newMode = $currentMode === 'board' ? 'list' : 'board';
        $this->putSession('kitchendisplay.viewMode', $newMode);

        return $this->onRefreshOrders();
    }

    public function onUpdateOrderStatus(): array
    {
        $validated = $this->validate(post(), [
            'itemId' => 'required|integer|min:1',
            'statusId' => 'required|integer|min:1',
        ]);

        throw_unless($order = Order::find(array_get($validated, 'itemId')),
            new FlashException(lang('igniterlabs.kitchendisplay::default.alert_order_not_found')),
        );

        $statusId = array_get($validated, 'statusId');
        throw_unless(Status::query()->where('status_id', $statusId)->exists(),
            new FlashException(lang('igniterlabs.kitchendisplay::default.alert_status_not_found')),
        );

        $order->updateOrderStatus($statusId);

        return $this->onRefreshOrders();
    }

    public function onUpdateWaitTime(): array
    {
        $validated = $this->validate(post(), [
            'itemId' => 'required|integer|min:1',
            'minutes' => 'required_if:customTime,null|integer',
            'customTime' => 'required_if:minutes,null|string',
        ]);

        throw_unless($order = Order::find(array_get($validated, 'itemId')),
            new FlashException(lang('igniterlabs.kitchendisplay::default.alert_order_not_found')),
        );

        $currentTime = Carbon::createFromFormat('H:i:s', $order->order_time);
        $newTime = ($customTime = array_get($validated, 'customTime'))
            ? Carbon::createFromFormat('H:i', $customTime)
            : $currentTime->addMinutes((int)array_get($validated, 'minutes'));

        $order->order_time = $newTime->format('H:i:s');
        $order->save();

        return $this->onRefreshOrders();
    }

    public function onRefreshOrders(): array
    {
        $this->prepareVars();

        return [
            '[data-control="kitchen-display"]' => $this->getSession('kitchendisplay.viewMode', 'board') === 'board'
                ? $this->makePartial('kitchendisplay/board')
                : $this->makePartial('kitchendisplay/list'),
        ];
    }

    protected function getBoardColumns(): Collection
    {
        $availableStatuses = $this->getAvailableStatuses();
        $allColumns = $this->model->getVisibleBoardColumns();
        $visibleColumns = $allColumns
            ->filter(fn(array $column): bool => array_get($column, 'code') !== 'on-hold')
            ->values();

        return $allColumns->map(function(array $column) use ($availableStatuses, $visibleColumns): stdClass {
            $statusId = (int)array_get($column, 'statusId', 0);
            $status = $statusId !== 0 ? $availableStatuses->firstWhere('status_id', $statusId) : null;

            $currentIndex = $statusId !== 0 ? $visibleColumns->search(fn(array $col): bool => (int)array_get($col, 'statusId') === $statusId) : false;
            $nextStatusId = ($currentIndex !== false && isset($visibleColumns[$currentIndex + 1]))
                ? (int)array_get($visibleColumns[$currentIndex + 1], 'statusId')
                : (array_get($column, 'code') === 'on-hold'
                    ? (int)array_get($visibleColumns->firstWhere('code', 'preparing'), 'statusId', 0)
                    : (int)array_get($visibleColumns->first(), 'statusId', 0)
                );

            $column['nextStatusId'] = $nextStatusId;
            $column['statusName'] = $status->status_name ?? '';
            $column['statusColor'] = $status->status_color ?? null;

            return (object)$column;
        });
    }

    protected function getBoardItems(): array
    {
        $eventResults = $this->fireEvent('kitchendisplay.getBoardItems', [$this->model]);

        $generatedEvents = [];
        if (count($eventResults) > 0) {
            $generatedEvents = array_merge(...$eventResults);
        }

        return $generatedEvents;
    }

    protected function getAvailableStatuses(): Collection
    {
        $visibleStatusIds = $this->model->getVisibleBoardColumns()->pluck('statusId')->filter()->all();
        if (!$visibleStatusIds) {
            return collect();
        }

        return Status::query()->whereIn('status_id', $visibleStatusIds)->get();
    }
}
