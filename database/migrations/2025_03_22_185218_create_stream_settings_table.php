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
        Schema::create('stream_settings', function (Blueprint $table) {
            $table->id();
            $table->string('ip')->nullable();
            $table->string('port')->nullable();
            $table->timestamps();
        });
        DB::table('stream_settings')->insert(['ip' => null, 'port' => null]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stream_settings');
    }
};
