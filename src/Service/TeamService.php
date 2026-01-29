<?php
namespace App\Service;
   
use App\Entity\Team;
use Doctrine\Persistence\ManagerRegistry;
   
class TeamService{
    private $doctrine;
   
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }
    public function getTeams(): ?array{
        $repository = $this->doctrine->getRepository(Team::class);
        return $repository->findAll();
    }
}