<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pro_plans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255)->nullable();
            $table->decimal('price', 10, 2)->nullable()->default(0);
            $table->decimal('transaction_fee', 10, 2)->nullable();
            $table->integer('discount_type')->nullable()->comment('2-fixed & 1->percent');
            $table->integer('apply_discount')->nullable()->comment('1->one_time & 2-recurring & 3->first_purchase');
            $table->decimal('discount_amount', 12)->nullable();
            $table->integer('govt_charges_type')->nullable()->comment('fixed->2 & percent->1');
            $table->integer('apply_govt_charges')->nullable()->comment('tax-1 & other_charges->2');
            $table->decimal('govt_charges_amt', 12)->nullable();
            $table->integer('plan_id')->unique();
            $table->decimal('discount', 10, 2);
            $table->decimal('tax', 10, 2);
            $table->decimal('trans_amount', 10, 2);
            $table->integer('total')->nullable();
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pro_plans');
    }
};