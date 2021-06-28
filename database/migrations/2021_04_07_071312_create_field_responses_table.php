<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFieldResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('field_responses', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('form_field_id');
            $table->unsignedInteger('form_response_id');
            $table->text('answer')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('field_responses', function (Blueprint $table) {
            $table->foreign('form_field_id')->references('id')->on('form_fields')->onDelete('cascade');
            $table->foreign('form_response_id')->references('id')->on('form_responses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('field_responses');
    }
}
