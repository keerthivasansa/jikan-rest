<?php

namespace App\Repositories;

use App\Contracts\Repository;
use App\Support\RepositoryQuery;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Support\Collection;

class DatabaseRepository extends RepositoryQuery implements Repository
{
    /**
     * @inheritDoc
     */
    public function createEntity()
    {
        return $this->queryable()->newModelInstance();
    }

    public function getByMalId(int $id)
    {
        $results = $this->getAllByMalId($id);

        return $results->isEmpty() ? null : $results->first();
    }

    public function getAllByMalId(int $id): Collection
    {
        return $this->queryByMalId($id)
            ->get();
    }

    public function queryByMalId(int $id): EloquentBuilder
    {
        return $this->queryable(true)
            ->where('mal_id', $id);
    }

    public function tableName(): string
    {
        return $this->queryable(true)->newModelInstance()->getTable();
    }

    // fixme: this should not be here.
    //        this is here because we have the "scrape" static method on models
    public function scrape(int $id): array
    {
        $modelClass = get_class($this->queryable(true)->newModelInstance());

        /** @noinspection PhpUndefinedMethodInspection */
        return $modelClass::scrape($id);
    }

    public function insert(array $attributes): bool
    {
        return $this->queryable(true)->insert($attributes);
    }
}
