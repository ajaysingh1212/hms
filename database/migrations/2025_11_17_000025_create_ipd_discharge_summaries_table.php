<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIpdDischargeSummariesTable extends Migration
{
    public function up()
    {
        Schema::create('ipd_discharge_summaries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('discharge_date')->nullable();
            $table->string('condition_on_discharge')->nullable();
            $table->longText('summary')->nullable();
            $table->longText('advice')->nullable();
            $table->date('followup_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
