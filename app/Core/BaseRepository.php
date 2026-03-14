<?php

namespace App\Core;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseRepository
{
    /**
     * The model instance.
     */
    protected Model $model;

    /**
     * Get all records with optional filters.
     */
    public function all(array $filters = []): Collection
    {
        $query = $this->model->query();

        foreach ($filters as $field => $value) {
            if (! is_null($value)) {
                $query->where($field, $value);
            }
        }

        return $query->get();
    }

    /**
     * Find a record by ID.
     */
    public function find(int|string $id): ?Model
    {
        return $this->model->find($id);
    }

    /**
     * Find a record by ID or fail.
     */
    public function findOrFail(int|string $id): Model
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Create a new record.
     */
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * Update an existing record.
     */
    public function update(int|string $id, array $data): Model
    {
        $record = $this->findOrFail($id);
        $record->update($data);

        return $record->fresh();
    }

    /**
     * Delete a record.
     */
    public function delete(int|string $id): bool
    {
        return $this->findOrFail($id)->delete();
    }

    /**
     * Paginate records.
     */
    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->query();

        foreach ($filters as $field => $value) {
            if (! is_null($value)) {
                $query->where($field, $value);
            }
        }

        return $query->paginate($perPage);
    }
}
