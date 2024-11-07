<?php

namespace App\UseCase\AddPlayerToTeam;

class Request
{
    public function __construct(private readonly string $teamName, private readonly string $playerName)
    {
    }

    public function getTeamName(): string
    {
        return $this->teamName;
    }

    public function getPlayerName(): string
    {
        return $this->playerName;
    }
}
