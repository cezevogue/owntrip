<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Form\ActivityType;
use App\Repository\ActivityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/activity')]
class ActivityController extends AbstractController
{
    #[Route('/create', name: 'activity_create')]
    public function index(Request $request, EntityManagerInterface $manager): Response
    {
        $activity=new Activity();

        $form=$this->createForm(ActivityType::class, $activity);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {

            $manager->persist($activity);
            $manager->flush();

            return $this->redirectToRoute('media_type_choice', ['param'=>'activity', 'id'=>$activity->getId() ]);

        }





        return $this->render('activity/index.html.twig', [
           'form'=>$form->createView()
        ]);
    }

         #[Route('/list', name: 'activity_list')]
          public function activity_list(ActivityRepository $activityRepository): Response
          {
              $activities=$activityRepository->findAll();



              return $this->render('activity/activity_list.html.twig', [
                 'activities'=>$activities
              ]);
          }




}
