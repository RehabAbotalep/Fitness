<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->date('dob');
            $table->float('weight');
            $table->float('height');
            $table->enum('gender', ['female', 'male']);
            $table->boolean('goal')->default(0);
            $table->boolean('is_verified')->default(0);
            $table->boolean('is_completed')->default(0);
            $table->string('social_id')->nullable();
            $table->enum('social_type', ['facebook', 'apple','google'])->nullable();
            $table->string('activation_code');
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
        Schema::dropIfExists('users');
    }
}
