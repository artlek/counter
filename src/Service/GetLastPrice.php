<?php

namespace App\Service;

use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\CounterPoint;

class GetLastPrice
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getData($id) : float
    {
        $repo = $this->em->getRepository(CounterPoint::class);
        $price = $repo->findOneBy(
            ['counter_id' => $id],
            ['datetime' => 'DESC']
        );
        if(empty($price)) {
            return 0;
        }
        else {
            return $price->getPrice();
        }
    }
}