<?php

namespace Codiliateur\LaravelModelExtensions\Tests;

use Codiliateur\LaravelModelExtensions\Database\Eloquent\CompositeKeyModel;
use Codiliateur\LaravelModelExtensions\Exceptions\NotCompositeKeyException;
use Codiliateur\LaravelModelExtensions\Tests\Models\BoardingPass;
use Codiliateur\LaravelModelExtensions\Tests\Models\TicketFlight;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SingleCompositeKeyModelTest extends AbstractSuite
{
    public function test_first()
    {
        $ticketFlight1 = TicketFlight::first();
        $this->assertTrue(
            $ticketFlight1 instanceof CompositeKeyModel,
            'Test : first() returns a CompositeKeyModel instance'
        );
    }

    public function test_get_key_name()
    {
        $ticketFlight1 = TicketFlight::first();
        $keyNames = $ticketFlight1->getKeyName();

        $this->assertIsArray(
            $keyNames,
            'Test : getKeyName() returns array'
        );
        $this->assertEquals(
            $keyNames,
            ['ticket_no', 'flight_id'],
            'Test : getKeyName() returns the expected array of columns'
        );
    }

    public function test_get_key()
    {
        $ticketFlight1 = TicketFlight::first();
        $key = $ticketFlight1->getKey();

        $this->assertIsArray(
            $key,
            'Test : getKey() returns array'
        );
        $this->assertEquals(
            $key,
            [$ticketFlight1->ticket_no, $ticketFlight1->flight_id],
            'Test : getKey() returns the expected array of key values'
        );
    }

    public function test_find_by_not_composite_key()
    {
        $this->expectException(NotCompositeKeyException::class);
        $ticketFlight1 = TicketFlight::find(1);
    }

    public function test_find_by_composite_key_as_array()
    {
        $ticketFlight1 = TicketFlight::first();
        $key = $ticketFlight1->getKey();
        $ticketFlight2 = TicketFlight::find($key);

        $this->assertTrue(
            $ticketFlight2 instanceof TicketFlight,
            'Test : find() returns a TicketFlight instance for existing keys'
        );
        $this->assertEquals(
            $ticketFlight2->getKey(),
            $key,
            'Test : find() found a TicketFlight with same key'
        );
    }

    public function test_find_by_composite_key_as_other_model()
    {
        $ticketFlight1 = TicketFlight::first();
        // TicketFlight and BoardingPass have same composite primary keys
        $borderPass1 = BoardingPass::find($ticketFlight1);

        $this->assertTrue(
            $borderPass1 instanceof BoardingPass,
            'Test : BoardingPass::find(TicketFlight) returns a BoardingPass instance'
        );
        $this->assertEquals(
            $borderPass1->getKey(),
            $ticketFlight1->getKey(),
            'Test : BoardingPass::find(TicketFlight) returns a BoardingPass with same key'
        );
    }

    public function test_find_by_non_existent_composite_key_as_array()
    {
        $ticketFlight1 = TicketFlight::find(['0000000000000', 1]);
        $this->assertNull(
            $ticketFlight1,
            'Test : find() returns NULL for non-existent keys'
        );
    }

    public function test_find_or_fail_by_non_existent_composite_key_as_array()
    {
        $this->expectException(ModelNotFoundException::class);
        TicketFlight::findOrFail(['0000000000000', 0]);
    }

    public function test_save_changed_model()
    {
        $boardingPass1 = BoardingPass::first();
        $key = $boardingPass1->getKey();

        $boardingPass1->boarding_no = $newBoardingNo = 999;
        $this->assertTrue(
            $boardingPass1->save(),
            'Test : save() returns TRUE'
        );
    }

    public function test_refresh_changed_model()
    {
        $boardingPass1 = BoardingPass::first();
        $boardingPass1->boarding_no = $newBoardingNo = 999;
        $boardingPass1->save();

        $boardingPass1->refresh();
        $this->assertEquals(
            $boardingPass1->boarding_no,
            $newBoardingNo,
            'Test : after refresh() model has changed column value'
        );
    }

    public function test_fresh_changed_model()
    {
        $boardingPass1 = BoardingPass::first();
        $boardingPass1->boarding_no = $newBoardingNo = 999;
        $boardingPass1->save();

        $boardingPass1->fresh();
        $this->assertEquals(
            $boardingPass1->boarding_no,
            $newBoardingNo,
            'Test : after fresh() model has changed column value'
        );
    }

    public function test_find_changed_model()
    {
        $boardingPass1 = BoardingPass::first();
        $key = $boardingPass1->getKey();
        $boardingPass1->boarding_no = $newBoardingNo = 999;
        $boardingPass1->save();

        $boardingPass2 = BoardingPass::find($key);
        $this->assertEquals(
            $boardingPass2->getKey(),
            $key,
            'Test : find() returns changed model'
        );
    }

    public function test_delete_model()
    {
        $boardingPass1 = BoardingPass::first();
        $key = $boardingPass1->getKey();

        $this->assertTrue(
            $boardingPass1->delete(),
            'Test : delete() returns TRUE'
        );

        $boardingPass2 = BoardingPass::find($key);
        $this->assertNull(
            $boardingPass2,
            'Test : deleted model not exists'
        );
    }
}