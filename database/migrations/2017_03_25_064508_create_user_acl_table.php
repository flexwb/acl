<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserAclTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_acl', function (Blueprint $table) {
            
            $table->increments('id');
            $table->unsignedInteger("acl_group_id");
            $table->unsignedInteger("res_id");
            $table->unsignedInteger("user_id");
            $table->boolean('read');
            $table->string('write');
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
        Schema::dropIfExists('user_acl');
    }
}
