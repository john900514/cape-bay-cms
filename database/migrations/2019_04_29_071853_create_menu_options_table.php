<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_options', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('route')->nullable();
            $table->string('page_shown')->nullable();
            $table->string('menu_name')->nullable();
            $table->string('permitted_role')->nullable();
            $table->boolean('active')->default(1);
            $table->integer('order')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->string('icon')->nullable();
            $table->string('onclick')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu_options');
    }
}
