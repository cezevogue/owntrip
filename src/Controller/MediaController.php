<?php

namespace App\Controller;

use App\Entity\Media;
use App\Form\MediaType;
use App\Repository\MediaTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MediaController extends AbstractController
{
    #[Route('/', name: 'media_type_choice')]
    public function index(MediaTypeRepository $repository): Response
    {
        $media_types=$repository->findAll();


        return $this->render('media/index.html.twig', [
            'media_types'=> $media_types
        ]);
    }




    #[Route('/create/{id}/{type}', name: 'media_create')]
    public function create(Request $request,EntityManagerInterface $manager, MediaTypeRepository $repository, $id, $type): Response
    {

        $media=new Media();

        $media_type=$repository->find($id);
        $media->setType($media_type);

        $form=$this->createForm(MediaType::class, $media);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($media);
            $manager->flush();

            $this->addFlash('success', 'Média créé');
            return $this->redirectToRoute('media_type_choice');
        }

        return $this->render('media/create.html.twig', [
            'form'=>$form->createView()
        ]);
    }
}