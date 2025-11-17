<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToIpdDischargeSummariesTable extends Migration
{
    public function up()
    {
        Schema::table('ipd_discharge_summaries', function (Blueprint $table) {
            $table->unsignedBigInteger('ipd_id')->nullable();
            $table->foreign('ipd_id', 'ipd_fk_10764074')->references('id')->on('ipd_admissions');
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->foreign('created_by_id', 'created_by_fk_10764084')->references('id')->on('users');
        });
    }
}
