<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kitchen_displays', function (Blueprint $table): void {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('title');
            $table->json('locations')->nullable();
            $table->json('order_statuses')->nullable();
            $table->json('order_types')->nullable();
            $table->json('menu_categories')->nullable();
            $table->unsignedInteger('orders_limit')->default(20);
            $table->date('display_from_date')->default(now());
            $table->json('column_new_statuses')->default(json_encode(['Received']));
            $table->boolean('column_new_visible')->default(true);
            $table->json('column_preparing_statuses')->default(json_encode(['Preparation']));
            $table->boolean('column_preparing_visible')->default(true);
            $table->json('column_ready_statuses')->default(json_encode(['Delivery']));
            $table->boolean('column_ready_visible')->default(true);
            $table->json('column_completed_statuses')->default(json_encode(['Completed']));
            $table->boolean('column_completed_visible')->default(true);
            $table->unsignedInteger('column_on_hold_status')->nullable();
            $table->boolean('column_on_hold_visible')->default(true);
            $table->json('hidden_card_fields')->default(json_encode([]));
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();
        });
    }
};
