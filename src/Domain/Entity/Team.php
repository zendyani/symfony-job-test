<?php

namespace App\Domain\Entity;

use App\Domain\Exception\ValidationException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Uid\Uuid;

class Team
{
    /**
     * @var Uuid
     */
    private Uuid $id;

    /**
     * @var string
     */
    private string $name;

    /**
     * A Team have many Players.
     * @var Collection<int, Player>
     */
    private Collection $players;

    /**
     * @throws ValidationException
     */
    public function __construct(string $name)
    {
        $this->id = Uuid::v4();
        if (mb_strlen($name) > 255) {
            throw new ValidationException("Team Name must have less than 255 characters.");
        }
        $this->name = $name;
        $this->players = new ArrayCollection();
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
     * @return Collection<int, Player>
     */
    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function addPlayer(Player $player): void
    {
        if (!$this->players->contains($player)) {
            $this->players->add($player);
            $player->setTeam($this); // Ensure bidirectional relationship is set
        }
    }

    public function removePlayer(Player $player): void
    {
        $this->players->removeElement($player);
    }
}
