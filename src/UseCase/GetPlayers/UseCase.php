<?php

namespace App\UseCase\GetPlayers;

use App\Domain\Repository\PlayerRepository;

final class UseCase
{
    public function __construct(private readonly PlayerRepository $playerRepository)
    {
    }

    /**
     * @param \App\UseCase\GetPlayers\Request $request
     * @return \App\UseCase\GetPlayers\Response
     */
    public function execute(Request $request): Response
    {
        return new Response($this->playerRepository->findAll());
    }
}
