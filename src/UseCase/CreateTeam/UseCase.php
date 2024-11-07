<?php

namespace App\UseCase\CreateTeam;

use App\Domain\Entity\Team;
use App\Domain\Exception\TeamNameValidationException;
use App\Domain\Repository\TeamRepository;
use App\UseCase\CreateTeam\Request;
use App\UseCase\CreateTeam\Response;

class UseCase
{
    public function __construct(private readonly TeamRepository $teamRepository)
    {
    }

    /**
     * @param \App\UseCase\CreateTeam\Request $request
     * @throws \App\Domain\Exception\TeamNameValidationException
     * @return \App\UseCase\CreateTeam\Response
     */
    public function execute(Request $request): Response
    {
        if ($this->teamRepository->doesNameExist($request->getName())) {
            throw new TeamNameValidationException(
                sprintf("Team name %s is already used, please chose another one.", $request->getName())
            );
        }

        $team = new Team($request->getName());
        $this->teamRepository->save($team);
        return new Response($team->getId());
    }
}
