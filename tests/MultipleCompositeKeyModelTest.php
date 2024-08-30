<?php

namespace Codiliateur\LaravelModelExtensions\Tests;

use Codiliateur\LaravelModelExtensions\Database\Eloquent\CompositeKeyModel;
use Codiliateur\LaravelModelExtensions\Tests\Models\BoardingPass;
use Codiliateur\LaravelModelExtensions\Tests\Models\TicketFlight;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MultipleCompositeKeyModelTest extends AbstractSuite
{
    public function test_take5_get_five()
    {
        $collection = TicketFlight::take(5)->get();

        $this->assertTrue(
            $collection instanceof Collection,
            'Test : take(5)->get() returns the Collection'
        );
        $this->assertEquals(
            $collection->count(),
            5,
            'Test : take(5)->get() returns collection with 5 items'
        );
        $this->assertTrue(
            $collection[0] instanceof TicketFlight,
            'Test : take(5)->get() returns collection of TicketFlight'
        );
    }

    public function test_find_by_composite_key_as_array()
    {
        $count = 3;
        $keys = TicketFlight::take($count)->get()->map(function ($item) {
            return $item->getKey();
        })->toArray();
        $collection = TicketFlight::find($keys);

        $this->assertTrue(
            $collection instanceof Collection,
            'Test : find(array[]) returns the Collection'
        );
        $this->assertEquals(
            $collection->count(),
            $count,
            'Test : find(array[]) returns collection with '.$count.' items'
        );
        $this->assertTrue(
            $collection[0] instanceof TicketFlight,
            'Test : find(array[]) returns collection of TicketFlight'
        );
        $foundModelKeys = $collection->map(function ($item) {
            return $item->getKey();
        })->toArray();
        $this->assertEquals(
            $foundModelKeys,
            $keys,
            'Test : find(array[]) returns collection of TicketFlight with searched keys'
        );
    }

    public function test_find_many_by_composite_key_as_array()
    {
        $count = 3;
        $keys = TicketFlight::take($count)->get()->map(function ($item) {
            return $item->getKey();
        })->toArray();
        $collection = TicketFlight::findMany($keys);

        $this->assertTrue(
            $collection instanceof Collection,
            'Test : findMany(array[]) returns the Collection'
        );
        $this->assertEquals(
            $collection->count(),
            $count,
            'Test : findMany(array[]) returns collection with '.$count.' items'
        );
        $this->assertTrue(
            $collection[0] instanceof TicketFlight,
            'Test : findMany(array[]) returns collection of TicketFlight'
        );
        $foundModelKeys = $collection->map(function ($item) {
            return $item->getKey();
        })->toArray();
        $this->assertEquals(
            $foundModelKeys,
            $keys,
            'Test : findMany(array[]) returns collection of TicketFlight with searched keys'
        );
    }

    public function test_find_by_composite_key_as_other_model()
    {
        $count = 3;
        $models = TicketFlight::take($count)->get();
        $collection = BoardingPass::find($models);

        $this->assertTrue(
            $collection instanceof Collection,
            'Test : find(array[]) returns the Collection'
        );
        $this->assertEquals(
            $collection->count(),
            $count,
            'Test : find(array[]) returns collection with '.$count.' items'
        );
        $this->assertTrue(
            $collection[0] instanceof BoardingPass,
            'Test : find(array[]) returns collection of BoardingPass'
        );
        $foundModelKeys = $collection->map(function ($item) {
            return $item->getKey();
        })->toArray();
        $searchingKeys = $models->map(function ($item) {
            return $item->getKey();
        })->toArray();
        $this->assertEquals(
            $foundModelKeys,
            $searchingKeys,
            'Test : find(array[]) returns collection of BoardingPass with searched keys'
        );
    }

    public function test_find_many_by_composite_key_as_other_model()
    {
        $count = 3;
        $models = TicketFlight::take($count)->get();
        $collection = BoardingPass::find($models);

        $this->assertTrue(
            $collection instanceof Collection,
            'Test : findMany(array[]) returns the Collection'
        );
        $this->assertEquals(
            $collection->count(),
            $count,
            'Test : findMany(array[]) returns collection with '.$count.' items'
        );
        $this->assertTrue(
            $collection[0] instanceof BoardingPass,
            'Test : findMany(array[]) returns collection of BoardingPass'
        );
        $foundModelKeys = $collection->map(function ($item) {
            return $item->getKey();
        })->toArray();
        $searchingKeys = $models->map(function ($item) {
            return $item->getKey();
        })->toArray();
        $this->assertEquals(
            $foundModelKeys,
            $searchingKeys,
            'Test : findMany(array[]) returns collection of BoardingPass with searched keys'
        );
    }

    public function test_find_by_non_existent_composite_keys_as_array()
    {
        $collection = TicketFlight::find([
            ['0000000000000', 1],
            ['0000000000001', 2]
        ]);

        $this->assertTrue(
            $collection instanceof Collection,
            'Test : find(array[]) returns Collection'
        );
        $this->assertTrue(
            $collection->isEmpty(),
            'Test : find(array[]) returns empty Collection'
        );
    }

    public function test_find_or_fail_throw_exception_1()
    {
        $keys = [
            ['0000000000000', 1],
            ['0000000000001', 2]
        ];

        $this->expectException(ModelNotFoundException::class);
        TicketFlight::findOrFail($keys);
    }

    public function test_find_or_fail_throw_exception_2()
    {
        $keys = [
            ['0000000000000', 1],
            TicketFlight::first()->getKey(),
        ];

        $this->expectException(ModelNotFoundException::class);
        TicketFlight::findOrFail($keys);
    }

}