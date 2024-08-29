<?php

namespace Codiliateur\LaravelModelExtensions\Tests\Models;

use Codiliateur\LaravelModelExtensions\Database\Eloquent\CompositeKeyModel;

/**
 * @property string $ticket_no
 * @property int $flight_id
 * @property string $fare_conditions
 * @property float $amount
 */
class TicketFlight extends CompositeKeyModel
{
    protected $primaryKey = [
        'ticket_no',
        'flight_id',
    ];
    public $timestamps = false;
    protected $casts = [];
}
