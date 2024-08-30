<?php

namespace Codiliateur\LaravelModelExtensions\Database\Eloquent;

use Codiliateur\LaravelModelExtensions\Exceptions\NotCompositeKeyException;
use Illuminate\Contracts\Database\Eloquent\Builder as BuilderContract;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Builder as BaseBuilder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Enumerable;

/**
 * @template TModel of CompositeKeyModel
 */
class CompositeKeyBuilder extends BaseBuilder implements BuilderContract
{

    /**
     * @return bool
     */
    protected function modelUseCompositeKey()
    {
        /**
         * @var CompositeKeyModel $model
         */
        $model = $this->getModel();

        return $model->useCompositeKey();
    }

    /**
     * Find a model by its primary key.
     *
     * @param mixed $id
     * @param array|string $columns
     *
     * @return ($id is (Arrayable<array-key,mixed>|array<mixed>) ? Collection<int, TModel|Model> : TModel|Model|null)
     */
    public function find($id, $columns = ['*'])
    {
        if ($this->modelUseCompositeKey()) {
            return $this->findByCompositeKey($id, $columns);
        }

        return parent::find($id, $columns);
    }

    /**
     * Find a model by its composite primary key.
     *
     * @param mixed $id
     * @param array|null $columns
     *
     * @return Collection<int, TModel|Model>|TModel|Model|null
     */
    protected function findByCompositeKey($id, $columns = ['*'])
    {
        /**
         * @var array $keyColumns
         */
        $keyColumns = $this->getModel()->getKeyName();

        $searchKeys = $this->normalizeCompositeKeysArgument($id);

        if (count($searchKeys) > 1) {
            return $this->findMany($id, $columns);
        }

        foreach ($keyColumns as $idx => $keyName) {
            $this->where($keyName, '=', $searchKeys[0][$idx]);
        }

        return $this->first($columns);
    }

    /**
     * Find multiple models by their primary keys.
     *
     * @param \Illuminate\Contracts\Support\Arrayable|array $ids
     * @param array|string $columns
     *
     * @return Collection<int, Model|CompositeKeyModel>
     */
    public function findMany($ids, $columns = ['*'])
    {
        if ($this->modelUseCompositeKey()) {
            return $this->findManyByCompositeKey($ids, $columns);
        }

        return parent::findMany($ids, $columns);
    }

    /**
     * Find multiple models by their composite primary keys.
     *
     * @param \Illuminate\Contracts\Support\Arrayable|array $ids
     * @param array|string $columns
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, Model>
     */
    public function findManyByCompositeKey($ids, $columns = ['*'])
    {
        /**
         * @var array $keyColumns
         */
        $keyColumns = $this->getModel()->getKeyName();
        $searchKeys = $this->normalizeCompositeKeysArgument($ids);

        $this->where(function ($query) use ($searchKeys, $keyColumns) {
            $operation = 'AND';
            foreach ($searchKeys as $idx => $searchKey) {
                $query->where(function ($query) use ($searchKey, $keyColumns) {
                    foreach (array_combine($keyColumns, $searchKey) as $column => $value) {
                        $query->where($column, '=', $value);
                    }
                }, null, null, $operation);
                $operation = 'OR';
            }
        });

        return $this->get($columns);
    }

    /**
     * Find a model by its primary key or throw an exception.
     *
     * @param mixed $id
     * @param array|string $columns
     *
     * @return ($id is (Arrayable<array-key, mixed>|array<mixed>) ?
     *         Collection<int, TModel|Model> : TModel|Model)
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException<TModel|Model>
     */
    public function findOrFail($id, $columns = ['*'])
    {
        if ($this->modelUseCompositeKey()) {
            $result = $this->find($id, $columns);
            $searchKeys = $this->normalizeCompositeKeysArgument($id);
            if (count($searchKeys) > 1) {
                if (count($result) !== count(array_unique($this->hashCompositeKeys($searchKeys)))) {
                    throw (new ModelNotFoundException())->setModel(
                        get_class($this->model),
                        $this->hashedCompositeKeysDiff($searchKeys, $result->modelKeys())
                    );
                }

                return $result;
            }

            if (is_null($result)) {
                throw (new ModelNotFoundException())->setModel(
                    get_class($this->model),
                    $this->hashCompositeKeys($searchKeys)
                );
            }

            return $result;
        }

        return parent::findOrFail($id, $columns);
    }

    /**
     * @param mixed $keys
     *
     * @return array
     *
     * @throws NotCompositeKeyException
     */
    protected function normalizeCompositeKeysArgument($keys)
    {
        if (is_array($keys) || $keys instanceof \Traversable) {
            if ($keys[0] instanceof Model) {
                if ($keys[0] instanceof CompositeKeyModel) {
                    return array_map(
                        function ($model) {
                            return $model->getKey();
                        },
                        $keys instanceof Enumerable ? $keys->all() : $keys
                    );
                }
                throw new NotCompositeKeyException('Model in argument $id must descends CompositeKeyModel');
            } elseif (is_array($keys[0])) {
                return $keys;
            } else {
                return [$keys];
            }
        } elseif ($keys instanceof Model) {
            if ($keys instanceof CompositeKeyModel) {
                return [$keys->getKey()];
            }
            throw new NotCompositeKeyException('Model in argument $id must descends CompositeKeyModel');
        }
        throw new NotCompositeKeyException('Must use composite key with CompositeKeyModel');
    }

    /**
     * Transform composite keys to strings
     *
     * @param array $keys
     *
     * @return string[]
     */
    protected function hashCompositeKeys(array $keys)
    {
        return array_map(function ($key) {
            return '('.implode(',', $key).')';
        }, $keys);
    }

    /**
     * @param array $keys1
     * @param array $keys2
     *
     * @return string[]
     */
    protected function hashedCompositeKeysDiff(array $keys1, array $keys2)
    {
        return array_diff($this->hashCompositeKeys($keys1), $this->hashCompositeKeys($keys2));
    }
}
