<?php

namespace App\UseCase\AddPlayerToTeam;

use App\Domain\Exception\AddPlayerToTeamFailedException;
use App\Domain\Exception\PlayerNotFoundException;
use App\Domain\Exception\TeamNotFoundException;
use App\Domain\Repository\PlayerRepository;
use App\Domain\Repository\TeamRepository;

class UseCase
{
    public function __construct(
        private readonly TeamRepository $teamRepository,
        private readonly PlayerRepository $playerRepository,
    ) {
    }

    public function execute(Request $request): void
    {
        if (!$team = $this->teamRepository->findOneByName($request->getTeamName())) {
            throw new TeamNotFoundException(sprintf("Team %s does not exist.", $request->getTeamName()));
        }

        if (!$player = $this->playerRepository->findOneByName($request->getPlayerName())) {
            throw new PlayerNotFoundException(sprintf("Team %s does not exist.", $request->getPlayerName()));
        }

        try {
            $team->addPlayer($player);
            $this->teamRepository->save($team);
        } catch (\Throwable $e) {
            throw new AddPlayerToTeamFailedException($e->getMessage());
        }
    }
}
