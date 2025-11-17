<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToOpdBillingsTable extends Migration
{
    public function up()
    {
        Schema::table('opd_billings', function (Blueprint $table) {
            $table->unsignedBigInteger('opd_id')->nullable();
            $table->foreign('opd_id', 'opd_fk_10763955')->references('id')->on('opd_visits');
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->foreign('created_by_id', 'created_by_fk_10763966')->references('id')->on('users');
        });
    }
}
