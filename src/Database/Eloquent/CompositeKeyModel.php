<?php

namespace Codiliateur\LaravelModelExtensions\Database\Eloquent;

use Codiliateur\LaravelModelExtensions\Database\Eloquent\Concerns\HasCompositeKey;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * The model with composite key
 */
abstract class CompositeKeyModel extends Model
{
    use HasCompositeKey;

    public $incrementing = false;
    public $keyType = 'composite';
}
