<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacilitiesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('facilities', function (Blueprint $table) {
      $table->id();
      $table->string('name')->nullable();
      $table->unsignedBigInteger('home_id')->nullable();
      $table->softDeletes();
      $table->timestamps();
      $table->foreign('home_id')->references('id')->on('homes');

    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('facilities');
  }
}
