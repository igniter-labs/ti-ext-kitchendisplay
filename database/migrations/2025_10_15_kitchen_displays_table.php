<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
            $table->date('display_from_date')->default(DB::raw('CURDATE()'));
            $table->json('board_columns')->nullable();
            $table->json('hidden_card_fields')->nullable();
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();
        });
    }
};
