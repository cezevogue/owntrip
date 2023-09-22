<?php

namespace App\Controller;

use App\Repository\MediaTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MediaController extends AbstractController
{
    #[Route('/media', name: 'media_type_choice')]
    public function index(MediaTypeRepository $repository): Response
    {
        $media_types=$repository->findAll();


        return $this->render('media/index.html.twig', [
          'media_types'=> $media_types
        ]);
    }
}
