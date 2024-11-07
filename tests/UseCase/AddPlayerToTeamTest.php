<?php

namespace App\Tests\UseCase;

use App\Domain\Entity\Player;
use App\Domain\Entity\Team;
use App\Domain\Exception\AddPlayerToTeamFailedException;
use App\Domain\Exception\PlayerNotFoundException;
use App\Domain\Exception\TeamNotFoundException;
use App\Domain\Repository\PlayerRepository;
use App\Domain\Repository\TeamRepository;
use App\UseCase\AddPlayerToTeam\Request;
use App\UseCase\AddPlayerToTeam\UseCase;
use PHPUnit\Framework\TestCase;

final class AddPlayerToTeamTest extends TestCase
{
    private TeamRepository $teamRepository;
    private PlayerRepository $playerRepository;

    protected function setUp(): void
    {
        $this->teamRepository = $this->createMock(TeamRepository::class);
        $this->playerRepository = $this->createMock(PlayerRepository::class);
    }

    /**
     * @test
     */
    public function it_throws_exception_when_team_not_found(): void
    {
        $request = new Request('NonExistentTeam', 'PlayerName');

        $this->teamRepository->expects($this->once())
            ->method('findOneByName')
            ->with($request->getTeamName())
            ->willReturn(null);

        $this->expectException(TeamNotFoundException::class);
        (new UseCase($this->teamRepository, $this->playerRepository))->execute($request);
    }

    /**
     * @test
     */
    public function it_throws_exception_when_player_not_found(): void
    {
        $request = new Request('TeamName', 'NonExistentPlayer');

        $team = $this->createMock(Team::class);
        $this->teamRepository->expects($this->once())
            ->method('findOneByName')
            ->with($request->getTeamName())
            ->willReturn($team);

        $this->playerRepository->expects($this->once())
            ->method('findOneByName')
            ->with($request->getPlayerName())
            ->willReturn(null);

        $this->expectException(PlayerNotFoundException::class);
        (new UseCase($this->teamRepository, $this->playerRepository))->execute($request);
    }

    /**
     * @test
     */
    public function it_adds_player_to_team_successfully(): void
    {
        $request = new Request('TeamName', 'PlayerName');

        $team = $this->createMock(Team::class);
        $player = $this->createMock(Player::class);

        $this->teamRepository->expects($this->once())
            ->method('findOneByName')
            ->with($request->getTeamName())
            ->willReturn($team);

        $this->playerRepository->expects($this->once())
            ->method('findOneByName')
            ->with($request->getPlayerName())
            ->willReturn($player);

        $team->expects($this->once())
            ->method('addPlayer')
            ->with($player);

        $this->teamRepository->expects($this->once())
            ->method('save')
            ->with($team);

        (new UseCase($this->teamRepository, $this->playerRepository))->execute($request);
    }

    /**
     * @test
     */
    public function it_throws_exception_when_adding_player_fails(): void
    {
        $request = new Request('TeamName', 'PlayerName');

        $team = $this->createMock(Team::class);
        $player = $this->createMock(Player::class);

        $this->teamRepository->expects($this->once())
            ->method('findOneByName')
            ->with($request->getTeamName())
            ->willReturn($team);

        $this->playerRepository->expects($this->once())
            ->method('findOneByName')
            ->with($request->getPlayerName())
            ->willReturn($player);

        $team->expects($this->once())
            ->method('addPlayer')
            ->with($player)
            ->willThrowException(new \RuntimeException('Failed to add player'));

        $this->expectException(AddPlayerToTeamFailedException::class);

        (new UseCase($this->teamRepository, $this->playerRepository))->execute($request);
    }
}
