<?php

namespace App\Domain\Entity;

use App\Domain\Exception\ValidationException;
use Symfony\Component\Uid\Uuid;

class Player
{
    private Uuid $id;

    private string $name;

    /**
     * @var Team|null
     */
    private ?Team $team = null;

    /**
     * @throws ValidationException
     */
    public function __construct(string $name)
    {
        $this->id = Uuid::v4();
        if (mb_strlen($name) > 255) {
            throw new ValidationException("Name must have less than 255 characters.");
        }
        $this->name = $name;
    }

    /**
     * @return Uuid
     */
    public function getId(): Uuid
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Team|null
     */
    public function getTeam(): ?Team
    {
        return $this->team;
    }

    /**
     * @param Team|null $team
     */
    public function setTeam(?Team $team): void
    {
        $this->team = $team;
    }
}
