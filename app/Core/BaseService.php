<?php

namespace App\Core;

abstract class BaseService
{
    /**
     * The repository instance.
     */
    protected $repository;

    /**
     * Get all records with optional filters.
     */
    public function all(array $filters = [])
    {
        return $this->repository->all($filters);
    }

    /**
     * Find a record by ID.
     */
    public function find(int|string $id)
    {
        return $this->repository->find($id);
    }

    /**
     * Find a record by ID or fail.
     */
    public function findOrFail(int|string $id)
    {
        return $this->repository->findOrFail($id);
    }

    /**
     * Create a new record.
     */
    public function create(array $data)
    {
        return $this->repository->create($data);
    }

    /**
     * Update an existing record.
     */
    public function update(int|string $id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    /**
     * Delete a record.
     */
    public function delete(int|string $id): bool
    {
        return $this->repository->delete($id);
    }

    /**
     * Paginate records.
     */
    public function paginate(int $perPage = 15, array $filters = [])
    {
        return $this->repository->paginate($perPage, $filters);
    }
}
