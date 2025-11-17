<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddDoctorsTable extends Migration
{
    public function up()
    {
        Schema::create('add_doctors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('doctor_name');
            $table->string('available_days')->nullable();
            $table->string('appointment_slot_duration')->nullable();
            $table->string('max_patients_per_day')->nullable();
            $table->decimal('doctor_fee', 15, 2)->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
