<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('offer_stamps');

        Schema::create('client_stamps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_profile_id')->constrained('client_profiles')->cascadeOnDelete();
            $table->foreignId('business_profile_id')->constrained('business_profiles')->cascadeOnDelete();
            $table->timestamp('awarded_at')->useCurrent();
            $table->timestamp('redeemed_at')->nullable();

            $table->index(['client_profile_id', 'business_profile_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_stamps');
    }
};
