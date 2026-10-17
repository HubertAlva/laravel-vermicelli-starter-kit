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
        Schema::create('model_locks', function (Blueprint $table) {
            $table->id();
            $table->morphs('lockable');
            $table->string('reason')->nullable();
            $table->timestamps();

            $table->unique(['lockable_type', 'lockable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('model_locks');
    }
};
