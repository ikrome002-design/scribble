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
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pro_subscription_id')->constrained()->cascadeOnDelete();
            $table->foreignId('visitor_business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('checked_in_by')->nullable()->constrained('staff')->nullOnDelete();
            $table->foreignId('checked_out_by')->nullable()->constrained('staff')->nullOnDelete();
            $table->foreignId('edited_by')->nullable()->constrained('staff')->nullOnDelete();
            $table->string('fname')->nullable();
            $table->string('lname')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('image')->nullable();
            $table->string('id_number')->nullable();
            $table->datetime('check_in_time')->nullable();
            $table->datetime('check_out_time')->nullable();
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('visitors');
    }
};
