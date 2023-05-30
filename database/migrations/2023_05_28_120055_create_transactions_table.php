<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('numbtrans')->unique();
            $table->string('address');
            $table->integer('total_price');
            $table->integer('shipping_price');
            $table->bigInteger('user_id')->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->enum('status', ['PENDING', 'SUCCESS', 'PROGRESS', 'FAILED', 'CANCELED', 'CONFIRMED', 'ACCEPTED', 'UNPAY', 'PAYED'])->default('CONFIRMED');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
