<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIpdTestLabTestPivotTable extends Migration
{
    public function up()
    {
        Schema::create('ipd_test_lab_test', function (Blueprint $table) {
            $table->unsignedBigInteger('ipd_test_id');
            $table->foreign('ipd_test_id', 'ipd_test_id_fk_10764042')->references('id')->on('ipd_tests')->onDelete('cascade');
            $table->unsignedBigInteger('lab_test_id');
            $table->foreign('lab_test_id', 'lab_test_id_fk_10764042')->references('id')->on('lab_tests')->onDelete('cascade');
        });
    }
}
