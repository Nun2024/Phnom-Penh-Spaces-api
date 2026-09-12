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
        Schema::create('bookings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('space_id')->constrained('spaces')->onDelete('cascade');
            $table->foreignUuid('user_id')->nullable()->constrained('users')->onDelete('set null');
            
            // Client details
            $table->string('client_name');
            $table->string('client_email');
            $table->string('client_phone');
            
            $table->date('booking_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->json('selected_slots');
            
            $table->decimal('total_price', 10, 2);
            $table->decimal('service_fee', 8, 2)->default(5.00);
            $table->string('payment_status')->default('unpaid'); // 'unpaid', 'partial', 'paid'
            $table->text('staff_notes')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
