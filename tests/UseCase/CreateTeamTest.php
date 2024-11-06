<?php

namespace App\Tests\UseCase;

use App\Domain\Entity\Team;
use App\Domain\Exception\TeamNameValidationException;
use App\Domain\Repository\TeamRepository;
use App\UseCase\CreateTeam\Request;
use App\UseCase\CreateTeam\Response;
use App\UseCase\CreateTeam\UseCase;
use PHPUnit\Framework\TestCase;

final class CreateTeamTest extends TestCase
{
    private TeamRepository $teamRepository;

    public function setUp(): void
    {
        $this->teamRepository = $this->createMock(TeamRepository::class);
    }

    /**
     * @test
     */
    public function it_throw_exception_when_name_is_not_uniq(): void
    {
        $request = new Request('mouloudia');
        $this->teamRepository->expects($this->once())
            ->method("doesNameExist")
            ->with($request->getName())
            ->willReturn(true);

        $this->expectException(TeamNameValidationException::class);
        (new UseCase($this->teamRepository))->execute($request);
    }

    /**
     * @test
     */
    public function it_does_not_throw_exception_when_name_is_uniq(): void {
        $request = new Request('mouloudia');
        $this->teamRepository->expects($this->once())
            ->method("doesNameExist")
            ->with($request->getName())
            ->willReturn(false);

        $useCase = (new UseCase($this->teamRepository))->execute($request);
        $this->assertInstanceOf(Response::class, $useCase);
    }

    /**
     * @test
     */
    public function it_create_a_team(): void {
        $request = new Request('mouloudia');
        
        $this->teamRepository->expects($this->once())
            ->method("create")
            ->with($this->isInstanceOf(Team::class));

        $useCase = (new UseCase($this->teamRepository))->execute($request);
        $this->assertInstanceOf(Response::class, $useCase);
    }

}
