<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCinemaSchema extends Migration
{
    /** ToDo: Create a migration that creates all tables for the following user stories

    For an example on how a UI for an api using this might look like, please try to book a show at https://in.bookmyshow.com/.
    To not introduce additional complexity, please consider only one cinema.

    Please list the tables that you would create including keys, foreign keys and attributes that are required by the user stories.

    ## User Stories

     **Movie exploration**
     * As a user I want to see which films can be watched and at what times
     * As a user I want to only see the shows which are not booked out

     **Show administration**
     * As a cinema owner I want to run different films at different times
     * As a cinema owner I want to run multiple films at the same time in different showrooms

     **Pricing**
     * As a cinema owner I want to get paid differently per show
     * As a cinema owner I want to give different seat types a percentage premium, for example 50 % more for vip seat

     **Seating**
     * As a user I want to book a seat
     * As a user I want to book a vip seat/couple seat/super vip/whatever
     * As a user I want to see which seats are still available
     * As a user I want to know where I'm sitting on my ticket
     * As a cinema owner I dont want to configure the seating for every show
     */
    public function up()
    {
        // Create movies table
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('url');
            $table->timestamps();
        });

        Schema::create('cinemas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // Create bridge table
        Schema::create('movie_cinemas', function (Blueprint $table) {
            $table->unsignedBigInteger('movie_id');
            $table->unsignedBigInteger('cinema_id');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->timestamps();

            $table->foreign('movie_id')->references('id')->on('movies')->onDelete('cascade');
            $table->foreign('cinema_id')->references('id')->on('cinemas')->onDelete('cascade');
        });

        // Create seats table
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cinema_id');
            $table->timestamps();

            $table->foreign('cinema_id')->references('id')->on('cinemas')->onDelete('cascade');
        });

        // Create tickets_types table
        Schema::create('ticket_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('amount_percentage');
            $table->timestamps();
        });

        // Create movie tickets table
        Schema::create('movie_tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('movie_id');
            $table->unsignedBigInteger('cinema_id');
            $table->unsignedBigInteger('seat_id');
            $table->unsignedBigInteger('ticket_type_id');
            $table->timestamps();

            $table->foreign('movie_id')->references('id')->on('movies')->onDelete('cascade');
            $table->foreign('cinema_id')->references('id')->on('cinemas')->onDelete('cascade');
            $table->foreign('seat_id')->references('id')->on('seats')->onDelete('cascade');
            $table->foreign('ticket_type_id')->references('id')->on('ticket_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movies');
        Schema::dropIfExists('cinemas');
        Schema::dropIfExists('movie_cinemas');
        Schema::dropIfExists('seats');
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('ticket_types');
        Schema::dropIfExists('movie_tickets');
    }
}
