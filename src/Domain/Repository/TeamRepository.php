<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Team;

interface TeamRepository
{
    /**
     * Check if the name of a team is uniq or not
     * @param string $name
     * @return bool
     */
    public function doesNameExist(string $name): bool;

    /**
     * @param \App\Domain\Entity\Team $team
     * @return void
     */
    public function create(Team $team): void;

    /**
     * @return array<Team>
     */
    public function findAll(): array;
}
