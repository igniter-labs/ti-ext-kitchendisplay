<?php

declare(strict_types=1);

namespace IgniterLabs\KitchenDisplay\Tests\Http\Requests;

use IgniterLabs\KitchenDisplay\Http\Requests\KitchenDisplayRequest;

it('returns attribute labels correctly', function(): void {
    $request = new KitchenDisplayRequest;

    $attributes = $request->attributes();

    expect($attributes)->toBeArray()
        ->and($attributes)->toHaveKey('title')
        ->and($attributes)->toHaveKey('locations')
        ->and($attributes)->toHaveKey('order_types')
        ->and($attributes)->toHaveKey('menu_categories')
        ->and($attributes)->toHaveKey('is_enabled')
        ->and($attributes)->toHaveKey('orders_limit')
        ->and($attributes)->toHaveKey('board_columns')
        ->and($attributes)->toHaveKey('board_columns.*.code')
        ->and($attributes)->toHaveKey('board_columns.*.label')
        ->and($attributes)->toHaveKey('board_columns.*.statusId')
        ->and($attributes)->toHaveKey('board_columns.*.isVisible')
        ->and($attributes)->toHaveKey('board_columns.*.priority')
        ->and($attributes)->toHaveKey('hidden_card_fields');
});

it('returns validation rules correctly', function(): void {
    $request = new KitchenDisplayRequest;

    $rules = $request->rules();

    expect($rules)->toBeArray()
        ->and($rules)->toHaveKey('title')
        ->and($rules)->toHaveKey('locations')
        ->and($rules)->toHaveKey('order_types')
        ->and($rules)->toHaveKey('menu_categories')
        ->and($rules)->toHaveKey('is_enabled')
        ->and($rules)->toHaveKey('orders_limit')
        ->and($rules)->toHaveKey('board_columns')
        ->and($rules)->toHaveKey('board_columns.*.code')
        ->and($rules)->toHaveKey('board_columns.*.label')
        ->and($rules)->toHaveKey('board_columns.*.statusId')
        ->and($rules)->toHaveKey('board_columns.*.isVisible')
        ->and($rules)->toHaveKey('board_columns.*.priority')
        ->and($rules)->toHaveKey('hidden_card_fields')
        ->and($rules)->toHaveKey('hidden_card_fields.*')
        ->and($rules['title'])->toContain('required', 'string', 'between:2,255')
        ->and($rules['locations'])->toContain('nullable', 'array')
        ->and($rules['order_types'])->toContain('nullable', 'array')
        ->and($rules['menu_categories'])->toContain('nullable', 'array')
        ->and($rules['is_enabled'])->toContain('boolean')
        ->and($rules['orders_limit'])->toContain('required', 'integer', 'min:1', 'max:100')
        ->and($rules['board_columns'])->toContain('array')
        ->and($rules['board_columns.*.code'])->toContain('required', 'string')
        ->and($rules['board_columns.*.label'])->toContain('required', 'string')
        ->and($rules['board_columns.*.statusId'])->toContain('nullable', 'required_if:board_columns.*.isVisible,1', 'integer')
        ->and($rules['board_columns.*.isVisible'])->toContain('required', 'boolean')
        ->and($rules['board_columns.*.priority'])->toContain('required', 'integer')
        ->and($rules['hidden_card_fields'])->toContain('nullable')
        ->and($rules['hidden_card_fields.*'])->toContain('string');
});
