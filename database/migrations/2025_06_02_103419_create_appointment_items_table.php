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
        Schema::create('appointment_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id') // foreign key to media.id
                  ->constrained('appointments')->onDelete('cascade');
            $table->integer('unit_price')->nullable(); 
            $table->integer('quantity')->nullable(); 
            $table->integer('booking_id');
            $table->string('start_date')->nullable(); 
            $table->string('start_hour')->nullable();             
            $table->integer('total')->nullable();     
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment_items');
    }
};
