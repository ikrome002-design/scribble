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
        Schema::create('shortcode_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('trans_id', 255)->nullable()->unique();
            $table->foreignId('shortcode')->constrained('pro_subscriptions', 'shortcode')->cascadeOnDelete();
            $table->string('bill_ref_number')->nullable()->index();
            $table->decimal('amount', 10)->nullable();
            $table->string('third_party_id', 1000)->nullable()->unique('third_party_id');
            $table->string('conversation_id', 255)->nullable()->unique('conversation_id');
            $table->string('checkout_request_id', 255)->nullable()->unique('checkout_request_id');
            $table->decimal('balance', 50, 2)->nullable();

            $table->dateTime('transaction_date')->nullable();
            $table->string('transaction_type', 255)->nullable();
            $table->string('phone_number', 255)->nullable();
            $table->string('name', 255)->nullable();
            $table->string('status', 255)->nullable()->default('Completed');
            $table->timestamp('date_posted')->nullable()->useCurrent();
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
        Schema::dropIfExists('shortcode_transactions');
    }
};
