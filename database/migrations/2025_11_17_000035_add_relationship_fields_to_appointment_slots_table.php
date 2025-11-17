<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToAppointmentSlotsTable extends Migration
{
    public function up()
    {
        Schema::table('appointment_slots', function (Blueprint $table) {
            $table->unsignedBigInteger('select_doctor_id')->nullable();
            $table->foreign('select_doctor_id', 'select_doctor_fk_10763890')->references('id')->on('add_doctors');
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->foreign('created_by_id', 'created_by_fk_10763896')->references('id')->on('users');
        });
    }
}
