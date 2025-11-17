<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOpdVisitsTable extends Migration
{
    public function up()
    {
        Schema::create('opd_visits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('visit_date')->nullable();
            $table->time('visit_time')->nullable();
            $table->longText('symptoms')->nullable();
            $table->longText('diagnosis')->nullable();
            $table->longText('notes')->nullable();
            $table->string('visit_type')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
