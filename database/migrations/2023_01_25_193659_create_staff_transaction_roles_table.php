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
        Schema::create('staff_transaction_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pro_subscription_id')->constrained()->cascadeOnDelete();
            $table->foreignId('staff_id')->constrained()->cascadeOnDelete();
            $table->unique(['pro_subscription_id', 'staff_id']);
            $table->tinyInteger('last_24_hours')->default(0);
            $table->tinyInteger('last_one_month')->default(0);
            $table->tinyInteger('all')->default(0);
            $table->tinyInteger('daily_summary')->default(0);
            $table->tinyInteger('monthly_summary')->default(0);
            $table->tinyInteger('all_summary')->default(0);
            $table->tinyInteger('transaction_sms')->default(0);
            $table->tinyInteger('assign_roles')->default(0);
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
        Schema::dropIfExists('staff_transaction_roles');
    }
};
