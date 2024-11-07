<?php

namespace App\Tests\UseCase;

use App\Domain\Entity\Team;
use App\Domain\Repository\TeamRepository;
use App\UseCase\GetTeams\Response;
use App\UseCase\GetTeams\UseCase;
use PHPUnit\Framework\TestCase;

final class GetTeamsTest extends TestCase
{
    private TeamRepository $teamRepository;

    protected function setUp(): void
    {
        $this->teamRepository = $this->createMock(TeamRepository::class);
    }

    /**
     * @test
     */
    public function it_returns_a_response_with_all_teams(): void
    {
        $teams = [
            new Team('Team A'),
            new Team('Team B'),
        ];

        $this->teamRepository->expects($this->once())
            ->method('findAll')
            ->willReturn($teams);

        $useCase = new UseCase($this->teamRepository);
        $response = $useCase->execute();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals($teams, $response->getTeams());
    }
}
