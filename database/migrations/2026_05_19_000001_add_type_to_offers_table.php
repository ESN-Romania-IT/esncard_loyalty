<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->string('type')->default('discount')->after('title'); // 'discount' | 'stamp_card'
            $table->unsignedInteger('stamps_required')->nullable()->after('type');
        });
    }

    public function down(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->dropColumn(['type', 'stamps_required']);
        });
    }
};
