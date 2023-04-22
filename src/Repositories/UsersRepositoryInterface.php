<?php

namespace GeekBrains\Repositories;

use GeekBrains\Person\User;
use GeekBrains\Person\UUID;

interface UsersRepositoryInterface
{
    public function save(User $user): void;
    public function get(UUID $uuid): User;

    public function getByUsername(string $username): User;
}
