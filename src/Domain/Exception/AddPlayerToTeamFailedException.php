<?php

namespace App\Domain\Exception;

use Exception;

final class AddPlayerToTeamFailedException extends Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
