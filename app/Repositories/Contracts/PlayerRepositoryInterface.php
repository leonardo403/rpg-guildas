<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;

interface PlayerRepositoryInterface
{
    public function getConfirmedPlayers(): Collection;
    public function findById(int $id): ?object;
}
