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
        Schema::create('slots', function (Blueprint $table) {
            $table->id();
                       
            $table->string('weekday');
            $table->string('start_hour');
            $table->string('price');
            $table->string('sale_price')->nullable();
            $table->string('quantity');
            $table->integer('status');
            $table->timestamps();
            $table->foreignId('booking_id') // foreign key to media.id
                  ->constrained('bookings')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slots');
    }
};
