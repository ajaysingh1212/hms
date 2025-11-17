<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOpdBillingsTable extends Migration
{
    public function up()
    {
        Schema::create('opd_billings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('total', 15, 2)->nullable();
            $table->string('discount_type')->nullable();
            $table->decimal('paid', 15, 2)->nullable();
            $table->decimal('due', 15, 2)->nullable();
            $table->string('payment_type')->nullable();
            $table->longText('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
