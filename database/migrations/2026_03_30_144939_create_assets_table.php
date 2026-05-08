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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('code_asset')->unique();
            $table->string('name_asset');
            $table->string('category_asset');
            $table->enum('status_asset', ['available', 'borrowed', 'damaged'])->default('available');
            $table->date('purchase_date');
            $table->decimal('purchase_price', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
