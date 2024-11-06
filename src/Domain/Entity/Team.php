<?php

namespace App\Domain\Entity;

use App\Domain\Exception\ValidationException;
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
     * @throws ValidationException
     */
    public function __construct(string $name)
    {
        $this->id = Uuid::v4();
        if (mb_strlen($name) > 255) {
            throw new ValidationException("Team Name must have less than 255 characters.");
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
}
