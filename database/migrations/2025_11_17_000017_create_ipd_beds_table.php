<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIpdBedsTable extends Migration
{
    public function up()
    {
        Schema::create('ipd_beds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('bed_no')->nullable();
            $table->decimal('charges_per_day', 15, 2)->nullable();
            $table->string('status')->nullable();
            $table->longText('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
