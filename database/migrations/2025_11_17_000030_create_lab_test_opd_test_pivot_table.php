<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLabTestOpdTestPivotTable extends Migration
{
    public function up()
    {
        Schema::create('lab_test_opd_test', function (Blueprint $table) {
            $table->unsignedBigInteger('opd_test_id');
            $table->foreign('opd_test_id', 'opd_test_id_fk_10763948')->references('id')->on('opd_tests')->onDelete('cascade');
            $table->unsignedBigInteger('lab_test_id');
            $table->foreign('lab_test_id', 'lab_test_id_fk_10763948')->references('id')->on('lab_tests')->onDelete('cascade');
        });
    }
}
