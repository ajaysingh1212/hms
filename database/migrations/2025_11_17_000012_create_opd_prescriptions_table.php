<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOpdPrescriptionsTable extends Migration
{
    public function up()
    {
        Schema::create('opd_prescriptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('dosage')->nullable();
            $table->string('duration')->nullable();
            $table->longText('instructions')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
