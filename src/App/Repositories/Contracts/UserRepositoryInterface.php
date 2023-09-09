<?php

namespace App\Repositories\Contracts;

use App\Models\User;

interface UserRepositoryInterface
{
    /**
     * Find a user by id
     *
     * @param int $id
     * @param array $relations
     * @return User|null
     */
    public function findById(int $id, array $relations = []): ?User;

    /**
     * Find a user by email address
     *
     * @param string $email
     * @param array $relations
     * @return User|null
     */
    public function findByEmail(string $email, array $relations = []): ?User;

    /**
     * Create a new user
     *
     * @param array $data
     * @return User|bool
     */
    public function create(array $data): User|bool;

    /**
     * Update an user
     *
     * @param array $data
     * @param int $id
     * @return User|bool
     */
    public function update(array $data, int $id): User|bool;

    /**
     * Delete a user by id
     *
     * @param int $id
     * @return bool|null
     */
    public function delete(int $id): bool|null;
}
