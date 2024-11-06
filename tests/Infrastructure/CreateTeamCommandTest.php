<?php

namespace App\Tests\Infrastructure;

use App\Infrastructure\Symfony\Command\CreateTeamCommand;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

class CreateTeamCommandTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();

        // Truncate the team table to clean up before each test
        $connection = $this->entityManager->getConnection();
        $connection->executeStatement('TRUNCATE TABLE team RESTART IDENTITY CASCADE');
    }
    
    /**
     * @test
     */
    public function it_execute_and_create_team(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find(CreateTeamCommand::COMMAND_NAME);
        $commandTester = new CommandTester($command);
        $r = $commandTester->execute([
            CreateTeamCommand::ARGUMENT_TEAM_NAME => 'name'
        ]);
        $this->assertSame(Command::SUCCESS, $r);
    }

    /**
     * @test
     */
    public function it_not_working_with_more_than_255_characters(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find(CreateTeamCommand::COMMAND_NAME);
        $commandTester = new CommandTester($command);
        $r = $commandTester->execute([
            CreateTeamCommand::ARGUMENT_TEAM_NAME => 'm46vsiOVh8f0d91SRtHxLd6q6L7l3skghubbLohDhdoUiUUMD1tOU8ijuqPV1SDcr4IuWZ6s7zE2nkSZb8n0SBj9uOodybXeHuqVaMDXCIIjcder3piE9ZrxVNUktEgh7fqbPHQhwBYsjjqg4v2vEXVQWUSpu4aMtxrXuqrRlKvsGTc4IqTs6MomFeqOByJB0NTYD3v1kMiR6xUem4BllRAYw67tnWCA2tFCAy3ifOpvI9Rnfvyn8MdBVtVVmyNR'
        ]);
        $this->assertSame(Command::FAILURE, $r);
    }

    /**
     * @test
     */
    public function it_fails_if_name_exist(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find(CreateTeamCommand::COMMAND_NAME);
        $commandTester = new CommandTester($command);

        $commandTester->execute([CreateTeamCommand::ARGUMENT_TEAM_NAME => 'Mouloudia']);
        $r = $commandTester->execute([CreateTeamCommand::ARGUMENT_TEAM_NAME => 'Mouloudia']);

        $this->assertSame(Command::FAILURE, $r);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->getConnection()->close();
    }
}