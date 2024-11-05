<?php

namespace App\UseCase\GetPlayers;

use App\Domain\Entity\Player;

final class Response
{
    /**
     * @param array<Player> $players
     */
    public function __construct(private readonly array $players)
    {
    }

    /**
     * @return array<Player>
     */
    public function getPlayers(): array
    {
        return $this->players;
    }
}
