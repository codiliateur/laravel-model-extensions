<?php

namespace Codiliateur\LaravelModelExtensions\Database\Eloquent\Concerns;

use Codiliateur\LaravelModelExtensions\Database\Eloquent\CompositeKeyModel;
use Illuminate\Contracts\Database\Eloquent\Builder as BuilderContract;
use Illuminate\Database\Eloquent\Builder;
use Codiliateur\LaravelModelExtensions\Database\Eloquent\CompositeKeyBuilder;
 use Illuminate\Database\Eloquent\Model;

trait HasCompositeKey
{
    protected static string $builder = CompositeKeyBuilder::class;

    /**
     * @var array|string
     */
    protected $primaryKey;

    /**
     * @return array<int, string>|string
     */
    public function getKeyName()
    {
        return $this->primaryKey;
    }

    /**
     * @return bool
     */
    public function useCompositeKey()
    {
        return is_array($this->getKey());
    }

    /**
     * @return array<int, string>|string
     */
    public function getQualifiedKeyName()
    {
        /**
         * @var string|string[] $keyName
         */
        $keyName = $this->getKeyName();
        if (is_array($keyName)) {
            return array_map(function ($keyNameSegment) {
                return $this->qualifyColumn($keyNameSegment);
            }, $keyName);
        }

        return $this->qualifyColumn($this->getKeyName());
    }

    /**
     * Get the value of the model's primary key.
     *
     * @return mixed
     */
    public function getKey()
    {
        /**
         * @var string|array<int, mixed> $keyName
         */
        $keyName = $this->getKeyName();
        if (is_array($keyName)) {
            return array_map(function ($keyNameSegment) {
                return $this->getAttribute($keyNameSegment);
            }, $keyName);
        }

        return parent::getKey();
    }

    /**
     * Set the keys for a save update query.
     *
     * @param  Builder<static> $query
     *
     * @return Builder<static>
     */
    protected function setKeysForSaveQuery($query)
    {
        return ($this->useCompositeKey())
            ? $this->setCompositeKeyForQuery($query)
            : parent::setKeysForSaveQuery($query);
    }

    protected function setKeysForSelectQuery($query)
    {
        return ($this->useCompositeKey())
            ? $this->setCompositeKeyForQuery($query)
            : parent::setKeysForSelectQuery($query);
    }

    /**
     * Set the composite primary key value for any query.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    protected function setCompositeKeyForQuery($query)
    {
        /**
         * @var array $keyName
         */
        $keyName = $this->getKeyName();
        foreach (array_combine($keyName, $this->getKeyForSaveQuery()) as $column => $value) {
            $query->where($column, '=', $value);
        }

        return $query;
    }

    /**
     * Get the primary key value for a save query.
     *
     * @return mixed
     */
    protected function getKeyForSaveQuery()
    {
        return ($this->useCompositeKey())
            ? $this->getCompositeKeyForQuery()
            : parent::getKeyForSaveQuery();
    }

    /**
     * Get the primary key value for a select query.
     *
     * @return mixed
     */
    protected function getKeyForSelectQuery()
    {
        return ($this->useCompositeKey())
            ? $this->getCompositeKeyForQuery()
            : parent::getKeyForSelectQuery();
    }

    /**
     * Get the composite primary key value for any query.
     *
     * @return array
     */
    protected function getCompositeKeyForQuery()
    {
        /**
         * @var array $keyName
         */
        $keyName = $this->getKeyName();
        $key = array_combine($keyName, $this->getKey());
        return array_map(function ($column) use ($key) {
            return $this->original[$column] ?? $key[$column];
        }, $keyName);
    }
}
