<?php

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

class KitchenDisplay extends BaseWidget
{
    use ValidatesForm;

    public ?KitchenDisplayModel $model = null;

    public array $waitTimes = [];

    protected ?Collection $availableStatuses = null;

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
            ->filter(function (BoardItem $item) use ($boardColumn) {
                return $item->statusId === (int)$boardColumn->statusId;
            })
            ->all();
    }

    public function isHiddenCardField(string $field): bool
    {
        $hiddenFields = $this->model->hidden_card_fields ?: [];
        return in_array($field, $hiddenFields);
    }

    public function getCardStatuses(int $itemStatusId): array
    {
        $availableStatuses = $this->getAvailableStatuses();

        return $this->model->getVisibleBoardColumns()
            ->filter(fn(array $boardColumn) => array_get($boardColumn, 'code') !== 'on-hold' && ((int)array_get($boardColumn, 'statusId')) !== $itemStatusId)
            ->mapWithKeys(function(array $boardColumn) use ($availableStatuses) {
                $statusId = array_get($boardColumn, 'statusId');
                return [$statusId => $availableStatuses->firstWhere('status_id', $statusId)->status_name ?? ''];
            })
            ->filter()
            ->all();
    }

    public function getBoardColumnColor(int $itemStatusId): string
    {
        $status = $this->getAvailableStatuses()->firstWhere('status_id', $itemStatusId);

        return $status->status_color ?? '#d2d6de';
    }

    public function onViewToggle()
    {
        $currentMode = $this->getSession('kitchendisplay.viewMode', 'board');
        $newMode = $currentMode === 'board' ? 'list' : 'board';
        $this->putSession('kitchendisplay.viewMode', $newMode);

        return $this->onRefreshOrders();
    }

    public function onUpdateOrderStatus(): array
    {
        $validated = $this->validate(post(), [
            'itemId' => 'required|integer',
            'statusId' => 'required|integer',
        ]);

        throw_unless($order = Order::find(array_get($validated, 'itemId')),
            new FlashException(lang('igniterlabs.kitchendisplay::default.alert_order_not_found'))
        );

        $statusId = array_get($validated, 'statusId') ?: $this->model->getVisibleBoardColumns()->firstWhere('code', 'on-hold')['statusId'] ?? null;
        throw_unless(Status::query()->where('status_id', $statusId)->exists(),
            new FlashException(lang('igniterlabs.kitchendisplay::default.alert_status_not_found'))
        );

        $order->updateOrderStatus($statusId);

        return $this->onRefreshOrders();
    }

    public function onUpdateWaitTime(): array
    {
        $validated = $this->validate(post(), [
            'itemId' => 'required|integer',
            'minutes' => 'required_if:customTime,null|integer',
            'customTime' => 'required_if:minutes,null|integer',
        ]);

        throw_unless($order = Order::find(array_get($validated, 'itemId')),
            new FlashException(lang('igniterlabs.kitchendisplay::default.alert_order_not_found'))
        );

        $currentTime = Carbon::createFromFormat('H:i:s', $order->order_time);
        $newTime = ($customTime = array_get($validated, 'customTime'))
            ? Carbon::createFromFormat('H:i', $customTime)
            : $currentTime->addMinutes((int) array_get($validated, 'minutes'));

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

    protected function getBoardColumns(): array
    {
        return $this->model->getVisibleBoardColumns()->map(fn(array $boardColum) => (object) $boardColum)->all();
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
        if ($this->availableStatuses) {
            return $this->availableStatuses;
        }

        $visibleStatusIds = $this->model->getVisibleBoardColumns()->pluck('statusId')->filter()->all();
        if (! $visibleStatusIds) {
            return [];
        }

        return $this->availableStatuses = Status::query()->whereIn('status_id', $visibleStatusIds)->get();
    }
}
