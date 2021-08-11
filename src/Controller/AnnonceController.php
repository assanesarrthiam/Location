<?php

namespace App\Controller;

use App\Entity\Anounce;
use App\Form\AnounceType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AnounceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;


class AnnonceController extends AbstractController
{
    #[Route('/annonce', name: 'annonce')]
    public function index(AnounceRepository $Repo): Response
    {
        $annonces =  $Repo->findAll();
        return $this->render('annonce/index.html.twig', [

            'annonces' => $annonces
            // 'controller_name' => 'AnnonceController',
        ]);
    }

    #[Route('/annonce/creer', name: 'annonce_create')]
    public function create(Request $request, EntityManagerInterface $manager)
    {

        $anounce = new Anounce();
        $form = $this->createForm(AnounceType::class, $anounce);
        $form->handlerequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération de l'image uploader dans le formulaire
            $coverImage = $form->get('coverImage')->getData();
            if ($coverImage) {

                //Création d'un nom pour l'image récupérer avec son extension  
                $imageName = md5(uniqid()) . '.' . $coverImage->guessExtension();

                // On déplace l'image dans le répertoire cover_image_direcrtion avec le nom créé
                $coverImage->move(
                    $this->getParameter('cover_image_directory'),
                    $imageName
                );
            }
            $anounce->setCoverImage($imageName);
            $manager->persist($anounce);
            $manager->flush();

            //return $this->redirectToRoute('annonce_show', ['id' => $anounce->getId()]);
            return $this->redirectToRoute('annonce');
        }

        return $this->render('annonce/creer.html.twig', [
            'formAnnonce' => $form->createView()
        ]);
    }
}
