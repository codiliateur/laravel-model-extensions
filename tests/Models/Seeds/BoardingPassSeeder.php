<?php

namespace Codiliateur\LaravelModelExtensions\Tests\Models\Seeds;

class BoardingPassSeeder
{
    protected array $data = [
        [
            "ticket_no" => "0005432079221",
            "flight_id" => 36094,
            "boarding_no" => 23,
            "seat_no" => "1A",
        ],
        [
            "ticket_no" => "0005432801137",
            "flight_id" => 9563,
            "boarding_no" => 2,
            "seat_no" => "1A",
        ],
        [
            "ticket_no" => "0005433557112",
            "flight_id" => 164098,
            "boarding_no" => 20,
            "seat_no" => "1A",
        ],
        [
            "ticket_no" => "0005434861552",
            "flight_id" => 65405,
            "boarding_no" => 44,
            "seat_no" => "1A",
        ],
        [
            "ticket_no" => "0005435834642",
            "flight_id" => 117026,
            "boarding_no" => 42,
            "seat_no" => "1A",
        ],
        [
            "ticket_no" => "0005432003235",
            "flight_id" => 89752,
            "boarding_no" => 10,
            "seat_no" => "1A",
        ],
        [
            "ticket_no" => "0005432003470",
            "flight_id" => 89913,
            "boarding_no" => 105,
            "seat_no" => "1A",
        ],
        [
            "ticket_no" => "0005432003656",
            "flight_id" => 90106,
            "boarding_no" => 82,
            "seat_no" => "1A",
        ],
        [
            "ticket_no" => "0005433567794",
            "flight_id" => 164215,
            "boarding_no" => 44,
            "seat_no" => "1A",
        ],
    ];

    public function run()
    {
        \DB::table('boarding_passes')->insert($this->data);
    }
}