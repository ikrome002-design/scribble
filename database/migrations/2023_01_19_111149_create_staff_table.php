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
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('fname');
            $table->string('lname');
            $table->string('mname')->nullable();
            $table->string('unique_id')->unique();
            $table->foreignId('staff_id')->comment('used store last added /edit in case it is staff')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('pro_subscription_id')->nullable()->constrained()->cascadeOnDelete();
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->integer('cl_id')->index('cl_id');
            $table->foreign('cl_id')->references('id')->on('sys_clients')->cascadeOnDelete();
            $table->string('id_number')->nullable()->index();
            $table->unique(['email', 'cl_id']);
            $table->string('phone_number');
            $table->string('image')->nullable();
            $table->enum('gender', ['Male', 'Female'])->nullable();
            $table->string('password')->nullable()->index();
            $table->enum('role', ['Manager', 'Cashier', 'Attendant', 'Security Personnel'])->default('Attendant');
            $table->smallInteger('first_login')->default(0);
            $table->rememberToken();
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
        Schema::dropIfExists('staff');
    }
};