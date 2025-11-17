<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIpdRoomsTable extends Migration
{
    public function up()
    {
        Schema::create('ipd_rooms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('room_no');
            $table->string('ward_type')->nullable();
            $table->decimal('charges_per_day', 15, 2)->nullable();
            $table->longText('notes')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
