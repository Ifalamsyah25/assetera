<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->string('merk_asset')->nullable()->after('category_asset');
            $table->string('lokasi_asset')->nullable()->after('merk_asset');
            $table->string('kondisi_asset')->nullable()->after('lokasi_asset');
            $table->text('deskripsi_asset')->nullable()->after('purchase_price');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->string('employee_id')->nullable()->after('asset_id');
            $table->string('division')->nullable()->after('employee_id');
            $table->string('asset_code_snapshot')->nullable()->after('division');
            $table->string('asset_category_snapshot')->nullable()->after('asset_code_snapshot');
            $table->string('asset_location_snapshot')->nullable()->after('asset_category_snapshot');
            $table->string('condition_note')->nullable()->after('asset_location_snapshot');
            $table->boolean('inspection_confirmed')->default(false)->after('condition_note');
        });

        Schema::table('maintenances', function (Blueprint $table) {
            $table->string('asset_name_snapshot')->nullable()->after('asset_id');
            $table->string('category_snapshot')->nullable()->after('asset_name_snapshot');
            $table->string('location_snapshot')->nullable()->after('category_snapshot');
            $table->date('checkin_date')->nullable()->after('location_snapshot');
            $table->string('current_condition')->nullable()->after('checkin_date');
            $table->date('estimated_completion_date')->nullable()->after('current_condition');
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn(['merk_asset', 'lokasi_asset', 'kondisi_asset', 'deskripsi_asset']);
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn([
                'employee_id',
                'division',
                'asset_code_snapshot',
                'asset_category_snapshot',
                'asset_location_snapshot',
                'condition_note',
                'inspection_confirmed',
            ]);
        });

        Schema::table('maintenances', function (Blueprint $table) {
            $table->dropColumn([
                'asset_name_snapshot',
                'category_snapshot',
                'location_snapshot',
                'checkin_date',
                'current_condition',
                'estimated_completion_date',
            ]);
        });
    }
};
