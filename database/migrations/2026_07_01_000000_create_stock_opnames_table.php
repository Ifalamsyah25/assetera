<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_opnames', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained('assets')->cascadeOnDelete();
            $table->integer('qty_sistem')->default(1);
            $table->integer('qty_aktual')->default(1);
            $table->integer('selisih')->default(0);
            $table->string('status'); // 'sesuai', 'selisih'
            $table->string('periode'); // e.g. 'Juni 2026'
            $table->date('tanggal_audit');
            $table->foreignId('auditor_id')->constrained('users')->cascadeOnDelete();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_opnames');
    }
};
