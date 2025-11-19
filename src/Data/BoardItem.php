<?php

declare(strict_types=1);

namespace IgniterLabs\KitchenDisplay\Data;

use Illuminate\Support\Collection;

class BoardItem
{
    public function __construct(
        public int $id,
        public int $statusId,
        public string $statusName,
        public string $customerName,
        public string $time,
        public string $formattedTime,
        public string $type,
        public string $typeName,
        public ?Collection $details = null,
        public array $availableStatuses = [],
    ) {}
}
