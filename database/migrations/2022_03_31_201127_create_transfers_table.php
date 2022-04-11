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
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->integer('start_city_id');
            $table->integer('finish_city_id');
            $table->integer('car_id');
            $table->float('tax');
            $table->decimal('price');
            $table->integer('cancel_time')->default(24);
            $table->integer('penalty')->default(50);
            $table->integer('company_id')->default(0);
            $table->integer('user_id')->default(1);
            $table->text('description')->nullable();


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
        Schema::dropIfExists('transfers');
    }
};
