<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use phpDocumentor\Reflection\Types\Nullable;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pro_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('shortcode')->nullable()->unsigned()->unique();
            $table->enum('shortcode_type', ['Paybill', 'Till'])->nullable();
            $table->string('business_name');
            $table->integer('cl_id')->index('cl_id');
            $table->foreign('cl_id')->references('id')->on('sys_clients')->cascadeOnDelete();
            $table->bigInteger('sender_id')->nullable()->index('sender_id');
            $table->enum('sub_status', ['Active', 'Inactive'])->default('Inactive');
            $table->enum('opted_out', ['Yes', 'No'])->default('No');
            $table->date('opted_out_date')->nullable();
            $table->enum('shortcode_status', ['Incomplete', 'Complete'])->nullable();
            $table->enum('developer_integrate', ['Calendly', 'Files'])->nullable();
            $table->date('plan_recurring_date')->nullable();
            $table->string('phone_number')->nullable();
            $table->tinyInteger('staff')->default(0);
            $table->tinyInteger('visitors')->default(0);
            $table->tinyInteger('transactions')->default(0);
            $table->time('summary_time');
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
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('pro_subscriptions');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
};
