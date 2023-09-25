<?php

namespace App\Controller;

use App\Entity\Media;
use App\Entity\MediaType as Type;
use App\Form\MediaType;
use App\Repository\MediaRepository;
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
        $media_types = $repository->findAll();


        return $this->render('media/index.html.twig', [
            'media_types' => $media_types
        ]);
    }


    #[Route('/create/{id}/{type}', name: 'media_create')]
    public function create(Request $request, EntityManagerInterface $manager, MediaTypeRepository $repository, $id, $type): Response
    {

        $media = new Media();

        $media_type = $repository->find($id);
        $media->setType($media_type);

        if ($media_type->getName()=='Lien'){

            $form = $this->createForm(MediaType::class, $media, ['lien'=>true]);
        }else{

            $form = $this->createForm(MediaType::class, $media, ['fichier'=>true]);
        }



        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($media->getType()->getName()!='Lien')
            {
                // on recupère le fichier uploader
                $file=$form->get('name')->getData();
                // on renomme le fichier en le concaténant avec date complète puis nom d'origine du fichier
                $file_name=date('YmdHis').'-'.$file->getClientOriginalName();

                // on upload
                $file->move($this->getParameter('upload_dir'), $file_name);

                // on reaffecte le renommage à l'objet
                $media->setName($file_name);



            }


            $manager->persist($media);
            $manager->flush();

            $this->addFlash('success', 'Média créé');

            if ($media->getType()->getName()=='Lien')
            {
                return $this->redirectToRoute('list_lien', ['id'=>$media->getType()->getId()]);

            }elseif ($media->getType()->getName()=='Vidéo')
            {
                return $this->redirectToRoute('list_video', ['id'=>$media->getType()->getId()]);

            }else
            {

                return $this->redirectToRoute('list_photo', ['id'=>$media->getType()->getId()]);
            }







        }

        return $this->render('media/create.html.twig', [
            'form' => $form->createView(),
            'type'=>$type
        ]);
    }

    #[Route('/list/video/{id}', name: 'list_video')]
    public function list_video(MediaRepository $repository, MediaTypeRepository $mediaTypeRepository, $id): Response
    {
        $media_type=$mediaTypeRepository->find($id);

        $medias=$repository->findBy(['type'=>$media_type]);


        return $this->render('media/video.html.twig', [
            'medias'=>$medias
        ]);
    }

    #[Route('/list/photo/{id}', name: 'list_photo')]
    public function list_photo(MediaRepository $repository, MediaTypeRepository $mediaTypeRepository, $id): Response
    {
        $media_type=$mediaTypeRepository->find($id);

        $medias=$repository->findBy(['type'=>$media_type]);

        return $this->render('media/photo.html.twig', [
            'medias'=>$medias
        ]);
    }

    #[Route('/list/lien/{id}', name: 'list_lien')]
    public function list_lien(MediaRepository $repository, MediaTypeRepository $mediaTypeRepository, $id): Response
    {
        $media_type=$mediaTypeRepository->find($id);

        $medias=$repository->findBy(['type'=>$media_type]);



        return $this->render('media/lien.html.twig', [
          'medias'=>$medias
        ]);
    }


         #[Route('/list/choice', name: 'list_choice')]
          public function list_choice(MediaTypeRepository $mediaTypeRepository): Response
          {
              $types=$mediaTypeRepository->findAll();

              return $this->render('media/list_choice.html.twig', [
                 'types'=>$types
              ]);
          }


            #[Route('/delete', name: 'delete_media')]
                public function delete_media(Request $request, EntityManagerInterface $manager, MediaRepository $repository): Response
                {

                  //  dd($request->request->all());
                $medias_id=$request->request->all()['choice'];

                foreach ($medias_id as $id)
                {
                    $media=$repository->find($id);
                    $manager->remove($media);

                }
                   $manager->flush();



                    return $this->redirectToRoute('list_choice');
                }




}
