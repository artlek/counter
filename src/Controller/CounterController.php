<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Counter;
use App\Entity\CounterPoint;
use App\Form\AddCounterType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Service\CounterExist;
use App\Service\GetCounters;
use App\Service\GetLastPrice;
use App\Service\GetLastPoint;
use App\Service\ComputeCounterUsage;
use App\Service\ComputePeriodicUsage;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;


class CounterController extends AbstractController
{
    #[Route('/counters', name: 'app_counter_list')]
    public function showCounterList(GetCounters $counters): Response
    {
        return $this->render('counter_list.html.twig', [
            'counters' => $counters->getData()
        ]);
    }

    #[Route('/counter/delete/{id}', name: 'app_counter_delete')]
    public function deleteCounter($id, EntityManagerInterface $em, Request $request): Response
    {
        $user = $this->getUser();
        $counter = $em->getRepository(Counter::class)->findOneBy(array
            ('userID' => $user->getId(),
            'id' => $id)
        );
        if($counter) {
            $counterPoint = $em->getRepository(CounterPoint::class)->findBy(
                ['counter_id' => $id]
            );
            $em->remove($counter);
            for($i = 0; $i < count($counterPoint); $i++) {
                $em->remove($counterPoint[$i]);
            }
            $em->flush();
            $this->addFlash(
                'positiveInfo',
                'Counter named ' . $counter->getName() . ' has been deleted'
            );
        }
        else {
            $this->addFlash(
                'negativeInfo',
                'Counter does not exist'
            );
        }
        return $this->redirectToRoute('app_counter_list');
    }

    #[Route('/counter/{id}', name: 'app_counter')]
    public function showCounter($id, EntityManagerInterface $em, Request $request, GetLastPrice $lastPrice, GetLastPoint $lastPoint, ComputeCounterUsage $usage, ComputePeriodicUsage $periodicUsage): Response
    {
        $user = $this->getUser();
        $pointRepo = $em->getRepository(CounterPoint::class);
        $points = $pointRepo->findBy([
            'counter_id' => $id,
            'user_id' => $user->getId()
        ]);
        $counterRepo = $em->getRepository(Counter::class);
        $counter = $counterRepo->findOneBy([
            'id' => $id,
            'userID' => $user->getId()
        ]);
        if($counter)
        {
            $point = new CounterPoint();
            $form = $this->createFormBuilder($point)
            ->add('countPoint', NumberType::class)
            ->add('price', NumberType::class, [
                'attr' => ['value' => $lastPrice->getData($id)]
                ])
            ->getForm();
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()) {
                if($form->getData()->getCountPoint() >= $lastPoint->getData($id)) {
                    $point = $form->getData();
                    $point->setCounterId($id);
                    $point->setDatetime(date("Y-m-d H:i:s"));
                    $point->setuserId($this->getUser()->getId());
                    $point->setPeriodicUsage($periodicUsage->compute($id, $point->getCountPoint()));
                    $em->persist($point);
                    $em->flush();
                    
                    $counter->setTotalUsage($usage->compute($id));
                    $em->persist($counter);
                    $em->flush();

                    $this->addFlash(
                        'positiveInfo',
                        'Counter point has been added'
                    );
                    return new RedirectResponse('/counter/'.$id);
                }
                else {
                    $this->addFlash(
                        'negativeInfo',
                        'Count point can not be lower than last point'
                    );
                    return new RedirectResponse('/counter/'.$id);
                }
            }
            return $this->render('counter.html.twig', [
                'counter' => $counter,
                'form' => $form,
                'points' => $points
            ]);
        }
        else
        {
            $this->addFlash(
                'negativeInfo',
                'Counter does not exist'
            );
            return $this->redirectToRoute('app_counter_list');
        }
    }

    #[Route('/counters/add', name: 'app_counter_add')]
    public function add(Request $request, EntityManagerInterface $em, CounterExist $counterExist): Response
    {
        $counter = new Counter();
        $form = $this->createForm(AddCounterType::class, $counter);
        $form->handleRequest($request);
        if ($form->isSubmitted() AND $form->isValid()) {
            $counter = $form->getData();
            $user = $this->getUser();
            if ($counterExist->ifExist($counter->getName())) {
                $this->addFlash(
                    'negativeInfo',
                    'Counter named ' . $counter->getName() . ' already exists'
                );

                return $this->redirectToRoute('app_counter_add');
            }
            else {
                $counter->setUserID($user->getId());
                $counter->setDatetime(date("Y-m-d H:i:s"));
                $counter->setTotalUsage(0);
                $em->persist($counter);
                $em->flush();
                $repo = $em->getRepository(Counter::class);
                $lastCounter = $repo->findOneBy(
                    ['userID' => $user->getId()],
                    ['id' => 'DESC']
                );
                $point = new CounterPoint();
                $point->setCountPoint($lastCounter->getStartPoint());
                $point->setCounterId($lastCounter->getId());
                $point->setUserId($lastCounter->getUserID());
                $point->setDatetime($lastCounter->getDateTime());
                $point->setPeriodicUsage(0);
                $point->setPrice($counter->getPrice());
                $em->persist($point);
                $em->flush();
                $this->addFlash(
                    'positiveInfo',
                    'Counter named ' . $counter->getName() . ' has been added'
                );
                return $this->redirectToRoute('app_counter_list');
            }
        }
        return $this->render('add_counter.html.twig',[
            'form' => $form
        ]);
    }
}
