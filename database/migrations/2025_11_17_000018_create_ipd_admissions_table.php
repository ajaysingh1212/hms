<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIpdAdmissionsTable extends Migration
{
    public function up()
    {
        Schema::create('ipd_admissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('admission_date')->nullable();
            $table->time('admission_time')->nullable();
            $table->string('condition_on_admission')->nullable();
            $table->longText('reason')->nullable();
            $table->string('status')->nullable();
            $table->string('ipd_number')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
