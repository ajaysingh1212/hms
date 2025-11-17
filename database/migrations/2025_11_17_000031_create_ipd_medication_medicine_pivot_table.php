<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIpdMedicationMedicinePivotTable extends Migration
{
    public function up()
    {
        Schema::create('ipd_medication_medicine', function (Blueprint $table) {
            $table->unsignedBigInteger('ipd_medication_id');
            $table->foreign('ipd_medication_id', 'ipd_medication_id_fk_10764063')->references('id')->on('ipd_medications')->onDelete('cascade');
            $table->unsignedBigInteger('medicine_id');
            $table->foreign('medicine_id', 'medicine_id_fk_10764063')->references('id')->on('medicines')->onDelete('cascade');
        });
    }
}
