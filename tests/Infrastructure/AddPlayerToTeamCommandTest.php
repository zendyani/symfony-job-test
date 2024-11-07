<?php

namespace App\Tests\Infrastructure;

use App\Domain\Entity\Player;
use App\Domain\Entity\Team;
use App\Infrastructure\Symfony\Command\AddPlayerToTeamCommand;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

class AddPlayerToTeamCommandTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();

        // Truncate the player and team tables to clean up before each test
        $connection = $this->entityManager->getConnection();
        $connection->executeStatement('TRUNCATE TABLE player RESTART IDENTITY CASCADE');
        $connection->executeStatement('TRUNCATE TABLE team RESTART IDENTITY CASCADE');
    }

    /**
     * @test
     */
    public function it_adds_player_to_team_successfully(): void
    {
        $application = new Application(self::$kernel);

        // Create and persist test team and player
        $team = new Team('Mouloudia');
        $player = new Player('PlayerName');
        $this->entityManager->persist($team);
        $this->entityManager->persist($player);
        $this->entityManager->flush();

        // Retrieve the command and run it with arguments
        $command = $application->find(AddPlayerToTeamCommand::COMMAND_NAME);
        $commandTester = new CommandTester($command);

        $result = $commandTester->execute([
            'team' => 'Mouloudia',
            'player' => 'PlayerName',
        ]);

        // Assert command success and check output
        $this->assertSame(Command::SUCCESS, $result);
        $this->assertStringContainsString(
            'Player "PlayerName" has been successfully added to team "Mouloudia".',
            $commandTester->getDisplay()
        );

        // Refresh team entity and verify player was added
        $this->entityManager->refresh($team);
        $this->assertTrue($team->getPlayers()->contains($player), 'Player was not added to the team.');
    }

    /**
     * @test
     */
    public function it_fails_if_team_does_not_exist(): void
    {
        $application = new Application(self::$kernel);

        // Create and persist only a player (no team)
        $player = new Player('PlayerName');
        $this->entityManager->persist($player);
        $this->entityManager->flush();

        $command = $application->find(AddPlayerToTeamCommand::COMMAND_NAME);
        $commandTester = new CommandTester($command);

        $result = $commandTester->execute([
            'team' => 'NonExistentTeam',
            'player' => 'PlayerName',
        ]);

        // Assert command failure and check output
        $this->assertSame(Command::FAILURE, $result);
        $this->assertStringContainsString('Team "NonExistentTeam" does not exist.', $commandTester->getDisplay());
    }

    /**
     * @test
     */
    public function it_fails_if_player_does_not_exist(): void
    {
        $application = new Application(self::$kernel);

        // Create and persist only a team (no player)
        $team = new Team('Mouloudia');
        $this->entityManager->persist($team);
        $this->entityManager->flush();

        $command = $application->find(AddPlayerToTeamCommand::COMMAND_NAME);
        $commandTester = new CommandTester($command);

        $result = $commandTester->execute([
            'team' => 'Mouloudia',
            'player' => 'NonExistentPlayer',
        ]);

        // Assert command failure and check output
        $this->assertSame(Command::FAILURE, $result);
        $this->assertStringContainsString('Player "NonExistentPlayer" does not exist.', $commandTester->getDisplay());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->getConnection()->close();
    }
}
