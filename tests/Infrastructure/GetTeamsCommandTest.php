<?php

namespace App\Tests\Infrastructure;

use App\Infrastructure\Symfony\Command\GetTeamsCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

class GetTeamsCommandTest extends KernelTestCase
{
    
    /**
     * @test
     */
    public function it_execute_and_create_team(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find(GetTeamsCommand::COMMAND_NAME);
        $commandTester = new CommandTester($command);
        $r = $commandTester->execute(array());
        $this->assertSame(Command::SUCCESS, $r);
    }
}