<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIpdVitalsTable extends Migration
{
    public function up()
    {
        Schema::create('ipd_vitals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->datetime('date_time')->nullable();
            $table->string('temperature')->nullable();
            $table->string('pulse')->nullable();
            $table->string('bp')->nullable();
            $table->string('spo_2')->nullable();
            $table->string('respiration')->nullable();
            $table->longText('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
