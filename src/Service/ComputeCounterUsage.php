<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\CounterPoint;

class ComputeCounterUsage
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function compute($counterId) : float
    {
        $repo = $this->em->getRepository(CounterPoint::class);
        $firstPoint = $repo->findOneBy(
            ['counter_id' => $counterId],
            ['id' => 'ASC']
        );
        $lastPoint = $repo->findOneBy(
            ['counter_id' => $counterId],
            ['id' => 'DESC']
        );
        return $lastPoint->getCountPoint() - $firstPoint->getCountPoint();
    }
}