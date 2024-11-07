<?php

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Entity\Team;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Team>
 * @method Team|null find($id, $lockMode = null, $lockVersion = null)
 * @method Team|null findOneBy(array $criteria, array $orderBy = null)
 * @method Team[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeamRepository extends ServiceEntityRepository implements \App\Domain\Repository\TeamRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Team::class);
    }

    public function doesNameExist(string $name): bool
    {
        $team = $this->findOneBy(array('name' => $name));
        return !!$team;
    }

    public function save(Team $team): void
    {
        $this->_em->persist($team);
        $this->_em->flush();
        $this->_em->refresh($team); // Ensure the team is fully refreshed after save
    }

    /**
     * @return Team[]
     */
    public function findAll(): array
    {
        return $this->findBy(array());
    }

    public function findOneByName(string $name): Team|null
    {
        return $this->findOneBy(array('name' => $name));
    }
}
