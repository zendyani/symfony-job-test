<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Player;

interface PlayerRepository
{
    /**
     * @param \App\Domain\Entity\Player $player
     * @return void
     */
    public function save(Player $player): void;

    /**
     * @return array<Player>
     */
    public function findAll(): array;

    public function findOneByName(string $name): ?Player;
}
