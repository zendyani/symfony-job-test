<?php

namespace App\Infrastructure\Symfony\Command;

use App\Domain\Exception\AddPlayerToTeamFailedException;
use App\Domain\Exception\PlayerNotFoundException;
use App\Domain\Exception\TeamNotFoundException;
use App\UseCase\AddPlayerToTeam\Request;
use App\UseCase\AddPlayerToTeam\UseCase;
use App\Domain\Repository\PlayerRepository;
use App\Domain\Repository\TeamRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: AddPlayerToTeamCommand::COMMAND_NAME,
    description: 'Assigns a player to a team.',
)]
final class AddPlayerToTeamCommand extends Command
{
    public const COMMAND_NAME = 'app:team:add-player';
    public const ARGUMENT_TEAM_NAME = 'team';
    public const ARGUMENT_PLAYER_NAME = 'player';

    public function __construct(
        private readonly UseCase $useCase,
        private readonly TeamRepository $teamRepository,
        private readonly PlayerRepository $playerRepository
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument(self::ARGUMENT_TEAM_NAME, InputArgument::REQUIRED, 'The name of the team')
            ->addArgument(self::ARGUMENT_PLAYER_NAME, InputArgument::REQUIRED, 'The name of the player');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $teamName = $input->getArgument(self::ARGUMENT_TEAM_NAME);
        $playerName = $input->getArgument(self::ARGUMENT_PLAYER_NAME);

        // Check if the team exists
        if (!$this->teamRepository->findOneByName($teamName)) {
            $io->error(sprintf('Team "%s" does not exist.', $teamName));
            return Command::FAILURE;
        }

        // Check if the player exists
        if (!$this->playerRepository->findOneByName($playerName)) {
            $io->error(sprintf('Player "%s" does not exist.', $playerName));
            return Command::FAILURE;
        }

        // Execute the use case to add the player to the team
        try {
            $request = new Request($teamName, $playerName);
            $this->useCase->execute($request);
            $io->success(sprintf('Player "%s" has been successfully added to team "%s".', $playerName, $teamName));
            return Command::SUCCESS;
        } catch (TeamNotFoundException | PlayerNotFoundException $e) {
            $io->error($e->getMessage());
            return Command::FAILURE;
        } catch (AddPlayerToTeamFailedException $e) {
            $io->error("Failed to add player to team: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
