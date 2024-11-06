<?php

namespace App\Infrastructure\Symfony\Command;

use App\UseCase\GetTeams\UseCase;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: self::COMMAND_NAME,
    description: 'Display teams.',
    hidden: false
)]
final class GetTeamsCommand extends Command
{
    public const COMMAND_NAME = "app:team:list";

    public function __construct(private readonly UseCase $useCase)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $response = $this->useCase->execute();

        $table = new Table($output);
        $rows = array();
        foreach ($response->getTeams() as $team) {
            $rows[] = array($team->getId(), $team->getName());
        }
        $table
            ->setHeaders(['Id', 'Name'])
            ->setRows($rows);
        $table->render();
        return Command::SUCCESS;
    }
}
