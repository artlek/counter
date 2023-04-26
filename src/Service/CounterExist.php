<?php

namespace App\Service;

use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Counter;

class CounterExist 
{
    private $em;
    private $security;

    public function __construct(Security $security, EntityManagerInterface $em)
    {
        $this->security = $security;
        $this->em = $em;
    }

    public function ifExist($name) : bool
    {
        $user = $this->security->getUser();
        $repo = $this->em->getRepository(Counter::class);
        $counters = $repo->findBy(array
            ('userID' => $user->getId(),
            'name' => $name)
        );
        if($counters) {
            return true;
        }
        else {
            return false;
        }
    }
}