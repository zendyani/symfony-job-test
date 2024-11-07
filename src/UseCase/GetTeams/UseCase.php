<?php

namespace App\UseCase\GetTeams;

use App\Domain\Repository\TeamRepository;

final class UseCase
{
    public function __construct(private readonly TeamRepository $teamRepository)
    {
    }

    /**
     * @return \App\UseCase\GetTeams\Response
     */
    public function execute(): Response
    {
        return new Response($this->teamRepository->findAll());
    }
}
