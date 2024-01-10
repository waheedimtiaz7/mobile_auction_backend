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
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('device_name')->nullable();
            $table->string('model')->nullable();
            $table->text('picture')->nullable();
            $table->string('os')->nullable();
            $table->string('ui')->nullable();
            $table->string('dimensions')->nullable();
            $table->string('weight')->nullable();
            $table->string('color')->nullable();
            $table->string('sim')->nullable();
            $table->string('cpu')->nullable();
            $table->string('gpu')->nullable();
            $table->string('size')->nullable();
            $table->string('resolution')->nullable();
            $table->string('ram')->nullable();
            $table->string('rom')->nullable();
            $table->string('sdcard')->nullable();
            $table->string('bluetooth')->nullable();
            $table->string('wifi')->nullable();
            $table->string('battery')->nullable();
            $table->string('price')->nullable();
            $table->string('suggest_price')->nullable();
            $table->unsignedBigInteger('bidder_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->enum('status',['Pending','Available','Sold','Rejected','In Transit', 'Received By Buyer','Closed'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
