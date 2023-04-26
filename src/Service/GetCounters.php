<?php

namespace App\Service;

use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Counter;

class GetCounters
{
    private $doctrine;
    private $security;

    public function __construct(Security $security, ManagerRegistry $doctrine)
    {
        $this->security = $security;
        $this->doctrine = $doctrine;
    }

    public function getData() : array
    {
        $user = $this->security->getUser();
        $repo = $this->doctrine->getRepository(Counter::class);
        $counters = $repo->findBy([
            'userID' => $user->getId()
        ]);
        return $counters;
    }
}