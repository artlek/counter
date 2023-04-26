<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\CounterPoint;

class ComputePeriodicUsage
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function compute($counterId, $countPoint) : float
    {
        $repo = $this->em->getRepository(CounterPoint::class);
        $points = $repo->findBy(
            ['counter_id' => $counterId],
            ['id' => 'DESC']
        );
        if(count($points) >= 1) {
            return $countPoint - $points[0]->getCountPoint();
        }
        else {
            return 0;
        }
    }
}