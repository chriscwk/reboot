<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->index();
            $table->string('event_title');
            $table->longText('event_description');
            $table->string('event_loca_address');
            $table->string('event_lat');
            $table->string('event_long');
            $table->dateTime('event_start_time');
            $table->dateTime('event_end_time');
            $table->string('event_status');
            $table->integer('event_max');
            $table->string('event_organizer');
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
        Schema::dropIfExists('events');
    }
}
