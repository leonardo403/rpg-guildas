<?php

namespace App\Repositories;

use App\Models\Player;
use App\Repositories\Contracts\PlayerRepositoryInterface;
use Illuminate\Support\Collection;

class PlayerRepository implements PlayerRepositoryInterface
{
    public function getConfirmedPlayers(): Collection
    {
        return Player::where('confirmed', true)->get();
    }

    public function findById(int $id): ?object
    {
        return Player::find($id);
    }
}
