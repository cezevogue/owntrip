<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\Media;
use App\Form\ActivityType;
use App\Form\MediaType;
use App\Repository\ActivityRepository;
use App\Repository\MediaRepository;
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
        $activity = new Activity();

        $form = $this->createForm(ActivityType::class, $activity);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($activity);
            $manager->flush();

            return $this->redirectToRoute('media_type_choice', ['param' => 'activity', 'id' => $activity->getId()]);

        }


        return $this->render('activity/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/list', name: 'activity_list')]
    public function activity_list(ActivityRepository $activityRepository): Response
    {
        $activities = $activityRepository->findAll();

        // dd($activities[2]->getMedias());


        return $this->render('activity/activity_list.html.twig', [
            'activities' => $activities
        ]);
    }

    #[Route('/update/infos/{id}', name: 'activity_update_infos')]
    public function update_infos(Request $request, Activity $activity, EntityManagerInterface $manager): Response
    {



        $form = $this->createForm(ActivityType::class, $activity);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($activity);
            $manager->flush();

            return $this->redirectToRoute('activity_list');

        }



        return $this->render('activity/activity_update_infos.html.twig', [
           'form'=>$form->createView()
        ]);
    }

    #[Route('/update/medias/{id}', name: 'activity_update_medias')]
    public function update_medias(ActivityRepository $activityRepository, MediaRepository $mediaRepository, EntityManagerInterface $manager,Request $request , $id): Response
    {

        $activity=$activityRepository->find($id);
        $medias=$mediaRepository->findBy(['activity'=>$activity]);

        return $this->render('activity/activity_update_medias.html.twig', [
           'medias'=>$medias,
            'id'=>$id
        ]);
    }


    #[Route('/delete/{id}', name: 'activity_delete')]
    public function delete(ActivityRepository $activityRepository, MediaRepository $mediaRepository, EntityManagerInterface $manager, $id): Response
    {
          $activity=$activityRepository->find($id);
          $medias=$mediaRepository->findBy(['activity'=>$activity]);

          foreach ($medias as $media)
          {

              if ($media->getType()->getName()!='Lien')
              {
                  unlink($this->getParameter('upload_dir').'/'.$media->getName());

              }

              $activity->removeMedia($media);


          }
          $manager->remove($activity);
          $manager->flush();



        return $this->redirectToRoute('activity_list');
    }

    #[Route('/details/{id}', name: 'activity_details')]
    public function details(Activity $activity): Response
    {


        return $this->render('activity/activity_details.html.twig', [
            'activity'=>$activity
        ]);
    }


}
