<?php

namespace Codiliateur\LaravelModelExtensions\Tests;

use Codiliateur\LaravelModelExtensions\Tests\Models\Seeds\BoardingPassSeeder;
use Codiliateur\LaravelModelExtensions\Tests\Models\Seeds\TicketFlightSeeder;
use Illuminate\Database\Schema\Blueprint;

abstract class AbstractSuite extends \Orchestra\Testbench\TestCase
{
    /**
     * Init
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
//        $this->withFactories(__DIR__.'/factories');
        $this->setUpDatabase($this->app);
        $this->setUpSeeder();
        \DB::enableQueryLog();
    }

    protected function setUpDatabase(\Illuminate\Foundation\Application $app)
    {
        $app['db']->connection()->getSchemaBuilder()->create('boarding_passes', function (Blueprint $table) {
            $table->string('ticket_no');
            $table->integer('flight_id');
            $table->string('boarding_no');
            $table->string('seat_no');

            $table->unique(['ticket_no','flight_id']);
            $table->unique(['flight_id','seat_no']);
        });

        $app['db']->connection()->getSchemaBuilder()->create('ticket_flights', function (Blueprint $table) {
            $table->string('ticket_no');
            $table->integer('flight_id');
            $table->string('fare_conditions');
            $table->decimal('amount',15,2);

            $table->unique(['ticket_no','flight_id']);
        });
    }

    protected function setUpSeeder()
    {
        $this->app->make(BoardingPassSeeder::class)->run();
        $this->app->make(TicketFlightSeeder::class)->run();
    }
}