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
        Schema::create('staff_visitor_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pro_subscription_id')->constrained()->cascadeOnDelete();
            $table->foreignId('staff_id')->constrained()->cascadeOnDelete();
            $table->unique(['pro_subscription_id', 'staff_id']);
            $table->tinyInteger('add')->default(0);
            $table->tinyInteger('edit')->default(0);
            $table->tinyInteger('delete')->default(0);
            $table->tinyInteger('view')->default(0);
            $table->tinyInteger('check_out')->default(0);
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
        Schema::dropIfExists('staff_visitor_roles');
    }
};
