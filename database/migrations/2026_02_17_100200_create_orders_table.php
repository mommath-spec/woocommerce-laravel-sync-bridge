<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('woo_id')->unique();
            $table->string('status', 32)->nullable()->index();
            $table->decimal('total', 12, 2)->nullable();
            $table->string('currency', 10)->nullable();
            $table->unsignedBigInteger('customer_woo_id')->nullable()->index();
            $table->json('raw_payload');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
