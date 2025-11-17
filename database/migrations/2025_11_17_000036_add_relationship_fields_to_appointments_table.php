<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToAppointmentsTable extends Migration
{
    public function up()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->unsignedBigInteger('department_id')->nullable();
            $table->foreign('department_id', 'department_fk_10763898')->references('id')->on('department_names');
            $table->unsignedBigInteger('doctor_id')->nullable();
            $table->foreign('doctor_id', 'doctor_fk_10763899')->references('id')->on('add_doctors');
            $table->unsignedBigInteger('available_slots_id')->nullable();
            $table->foreign('available_slots_id', 'available_slots_fk_10763900')->references('id')->on('appointment_slots');
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->foreign('created_by_id', 'created_by_fk_10763910')->references('id')->on('users');
        });
    }
}
