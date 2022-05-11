<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->integer('transfer_id');
            $table->integer('car_id');
            $table->integer('booking_id')->nullable();
            $table->integer('client_id')->nullable();
            $table->uuid('booking_token')->nullable();
            $table->text('name')->nullable();
            $table->string('currency')->default('TRY');
            $table->text('email')->nullable();
            $table->text('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('flight_number')->nullable();
            $table->decimal('amount')->nullable();
            $table->decimal('return_amount')->nullable();
            $table->decimal('total')->nullable();
            $table->string('step')->nullable();
            $table->tinyInteger('return_trip')->nullable();
            $table->integer('passengers_number')->default(1);
            $table->tinyInteger('add_child_seat')->nullable();
            $table->integer('client_confirmed')->nullable();
            $table->integer('booking_accepted')->nullable();
            $table->string('pay_type')->nullable();
            $table->tinyInteger('payment_confirmed')->nullable();
            $table->tinyInteger('pre_paid')->nullable();
            $table->integer('company_id')->default(0);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
    }
};
