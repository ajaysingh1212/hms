<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToIpdAdmissionsTable extends Migration
{
    public function up()
    {
        Schema::table('ipd_admissions', function (Blueprint $table) {
            $table->unsignedBigInteger('patient_id')->nullable();
            $table->foreign('patient_id', 'patient_fk_10763988')->references('id')->on('appointments');
            $table->unsignedBigInteger('doctor_id')->nullable();
            $table->foreign('doctor_id', 'doctor_fk_10763989')->references('id')->on('add_doctors');
            $table->unsignedBigInteger('room_id')->nullable();
            $table->foreign('room_id', 'room_fk_10763992')->references('id')->on('ipd_rooms');
            $table->unsignedBigInteger('bed_id')->nullable();
            $table->foreign('bed_id', 'bed_fk_10763993')->references('id')->on('ipd_beds');
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->foreign('created_by_id', 'created_by_fk_10764001')->references('id')->on('users');
        });
    }
}
