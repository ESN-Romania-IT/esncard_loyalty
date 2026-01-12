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
        Schema::create('offer_redemptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('offer_id')->constrained('offers')->cascadeOnDelete();
            $table->foreignId('client_profile_id')->constrained('user_profiles')->cascadeOnDelete();
            $table->foreignId('business_profile_id')->constrained('business_profiles')->cascadeOnDelete();
            $table->timestamp('redeemed_at')->nullable();
            $table->timestamps();

            $table->index(['client_profile_id', 'business_profile_id']);
            $table->index(['client_profile_id', 'offer_id']);
            $table->index(['business_profile_id', 'offer_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offer_redemptions');
    }
};
