<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToAddDoctorsTable extends Migration
{
    public function up()
    {
        Schema::table('add_doctors', function (Blueprint $table) {
            $table->unsignedBigInteger('select_department_id')->nullable();
            $table->foreign('select_department_id', 'select_department_fk_10763878')->references('id')->on('department_names');
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->foreign('created_by_id', 'created_by_fk_10763888')->references('id')->on('users');
        });
    }
}
