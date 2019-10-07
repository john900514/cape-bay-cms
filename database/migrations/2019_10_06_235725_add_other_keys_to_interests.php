<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOtherKeysToInterests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exponent_push_notification_interests', function (Blueprint $table) {
            $table->integer('entity_id')->nullable();
            $table->string('entity_type')->nullable();
            $table->bigIncrements('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exponent_push_notification_interests', function (Blueprint $table) {
            $table->dropColumn('entity_id', 'entity_type');
        });
    }
}
