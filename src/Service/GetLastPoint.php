<?php

namespace App\Service;

use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\CounterPoint;
use App\Entity\Counter;

class GetLastPoint
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getData($id) : float
    {
        $counterPointRepo = $this->em->getRepository(CounterPoint::class);
        $point = $counterPointRepo->findOneBy(
            ['counter_id' => $id],
            ['datetime' => 'DESC']
        );
        if(empty($point)) {
            $counterRepo = $this->em->getRepository(Counter::class);
            $counter = $counterRepo->fintOneBy(
                ['counter_id' => $id]
            );
            return $counter->getStartPoint();
        }
        else {
            return $point->getCountPoint();
        }
    }
}