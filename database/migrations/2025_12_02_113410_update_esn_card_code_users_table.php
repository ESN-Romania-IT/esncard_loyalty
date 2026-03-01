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
        Schema::table('users', function (Blueprint $table) {
        // Remove old name column
            $table->dropColumn('name');

            // Add new fields
            $table->string('first_name')->after('id');
            $table->string('last_name')->after('first_name');
            $table->string('esncard_code')->unique()->after('email');
            $table->string('role')->default('standard-user')->after('esncard_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
        // Restore original structure
            $table->string('name')->after('id');

            // Remove added fields
            $table->dropColumn(['first_name', 'last_name', 'esncard_code', 'role']);
        });
    }
};
