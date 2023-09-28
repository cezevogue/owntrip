<?php

namespace App\Controller;

use App\Repository\ActivityRepository;
use App\Repository\ActivityTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    #[Route('/', name: 'app_front')]
    public function index(): Response
    {
        return $this->render('front/index.html.twig', [
            'controller_name' => 'FrontController',
        ]);
    }


    #[Route('/activities/result', name: 'activities_result')]
    #[Route('/activities/filter', name: 'activities_filter')]
    public function activities_result(ActivityRepository $activityRepository,ActivityTypeRepository $typeRepository, Request $request): Response
    {
        $city=$request->request->get('city');
        $types=$typeRepository->findAll();

        if ($request->request->has('type')){
           $activities=array();
            foreach($request->request->all()['type'] as $type){

                $results=$activityRepository->findByCityAndType($city, $type);
                foreach ($results as $result){
                    $activities[]=$result;
                }

            }

        }else{

            $activities=$activityRepository->findByCity($city);

        }


       //dd($activities);



        return $this->render('front/activities_result.html.twig', [
            'activities'=>$activities,
            'types'=>$types,
            'city'=>$city
        ]);
    }


}
