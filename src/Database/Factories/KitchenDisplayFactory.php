<?php

declare(strict_types=1);

namespace IgniterLabs\KitchenDisplay\Database\Factories;

use Igniter\Flame\Database\Factories\Factory;
use IgniterLabs\KitchenDisplay\Models\KitchenDisplay;
use Override;

class KitchenDisplayFactory extends Factory
{
    protected $model = KitchenDisplay::class;

    #[Override]
    public function definition(): array
    {
        return [
            'title' => $this->faker->words(3, true),
            'locations' => null,
            'order_types' => null,
            'menu_categories' => null,
            'orders_limit' => 20,
            'board_columns' => null,
            'hidden_card_fields' => null,
            'is_enabled' => true,
        ];
    }

    public function enabled(): static
    {
        return $this->state(fn(array $attributes): array => [
            'is_enabled' => true,
        ]);
    }

    public function disabled(): static
    {
        return $this->state(fn(array $attributes): array => [
            'is_enabled' => false,
        ]);
    }

    public function withLocations(array $locationIds): static
    {
        return $this->state(fn(array $attributes): array => [
            'locations' => $locationIds,
        ]);
    }

    public function withOrderTypes(array $orderTypes): static
    {
        return $this->state(fn(array $attributes): array => [
            'order_types' => $orderTypes,
        ]);
    }

    public function withMenuCategories(array $categoryIds): static
    {
        return $this->state(fn(array $attributes): array => [
            'menu_categories' => $categoryIds,
        ]);
    }

    public function withBoardColumns(array $boardColumns): static
    {
        return $this->state(fn(array $attributes): array => [
            'board_columns' => $boardColumns,
        ]);
    }
}

