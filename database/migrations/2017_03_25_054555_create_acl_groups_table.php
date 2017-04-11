<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAclGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acl_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string("table");
            $table->string("acl_key_type");
            $table->string("acl_res_table")->nullable();
            $table->string("acl_res_field")->nullable();
            $table->string("acl_user_table")->nullable();
            $table->string("acl_user_field")->nullable();
            $table->string("acl_group_table");
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
        Schema::dropIfExists('acl_groups');
    }
}
