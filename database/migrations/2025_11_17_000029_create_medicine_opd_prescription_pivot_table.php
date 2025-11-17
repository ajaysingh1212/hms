<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicineOpdPrescriptionPivotTable extends Migration
{
    public function up()
    {
        Schema::create('medicine_opd_prescription', function (Blueprint $table) {
            $table->unsignedBigInteger('opd_prescription_id');
            $table->foreign('opd_prescription_id', 'opd_prescription_id_fk_10764064')->references('id')->on('opd_prescriptions')->onDelete('cascade');
            $table->unsignedBigInteger('medicine_id');
            $table->foreign('medicine_id', 'medicine_id_fk_10764064')->references('id')->on('medicines')->onDelete('cascade');
        });
    }
}
