<?php

namespace Codiliateur\LaravelModelExtensions\Tests\Models\Seeds;

class TicketFlightSeeder
{
    protected array $data = [
        [
            "ticket_no" => "0005432079221",
            "flight_id" => 36094,
            "fare_conditions" => "Business",
            "amount" => "99800.00",
        ],
        [
            "ticket_no" => "0005434861552",
            "flight_id" => 65405,
            "fare_conditions" => "Business",
            "amount" => "49700.00",
        ],
        [
            "ticket_no" => "0005432003235",
            "flight_id" => 89752,
            "fare_conditions" => "Business",
            "amount" => "99800.00",
        ],
        [
            "ticket_no" => "0005433567794",
            "flight_id" => 164215,
            "fare_conditions" => "Business",
            "amount" => "105900.00",
        ],
        [
            "ticket_no" => "0005432003470",
            "flight_id" => 89913,
            "fare_conditions" => "Business",
            "amount" => "99800.00",
        ],
        [
            "ticket_no" => "0005435834642",
            "flight_id" => 117026,
            "fare_conditions" => "Business",
            "amount" => "199300.00",
        ],
        [
            "ticket_no" => "0005432003656",
            "flight_id" => 90106,
            "fare_conditions" => "Business",
            "amount" => "99800.00",
        ],
        [
            "ticket_no" => "0005432949087",
            "flight_id" => 164161,
            "fare_conditions" => "Business",
            "amount" => "105900.00",
        ],
        [
            "ticket_no" => "0005432801137",
            "flight_id" => 9563,
            "fare_conditions" => "Business",
            "amount" => "150400.00",
        ],
        [
            "ticket_no" => "0005433557112",
            "flight_id" => 164098,
            "fare_conditions" => "Business",
            "amount" => "105900.00",
        ],
    ];

    public function run()
    {
        \DB::table('ticket_flights')->insert($this->data);
    }
}