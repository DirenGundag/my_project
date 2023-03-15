<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PlatRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Plat;
use App\Entity\Category;
use App\Form\PlatType;

class PlatController extends AbstractController
{
    /** Lecture d'un plat */

    #[Route('/plat/{id}', name: 'app_platview')]
    public function index(ManagerRegistry $doctrine,PlatRepository $platRepository, int $id): Response
    {
        // Entity Manager de Symfony
        $entityManager = $doctrine->getManager();
        $platRepository = $entityManager->getRepository(Plat::class);

        // On récupère le plat qui correspond à l'id passé dans l'url
        $plat = $platRepository->findBy(['id' => $id]);

        return $this->render('plat/index.html.twig', [
            'controller_name' => 'PlatController',
            'plat' => $plat,
        ]);
    }
    #[Route('/listePlat', name: 'app_plat')]
    public function listePlat(ManagerRegistry $doctrine,PlatRepository $platRepository): Response
    {

         // Entity Manager de Symfony
        $entityManager = $doctrine->getManager();
        $platRepository = $entityManager->getRepository(Plat::class);
         // On récupère tous les articles disponibles en base de données
        $plats   = $platRepository->findAll();
        return $this->render('plat/listePlat.html.twig', [
            'plats'  => $plats
        ]);

    }
    /**Modification d'un plat */
    #[Route('/editPlat/{id}', name: 'plat_edit')]
    public function edit(ManagerRegistry $doctrine,PlatRepository $platRepository, int $id=null, Request $request): Response
    {
        // Entity Manager de Symfony
        $entityManager = $doctrine->getManager();
        $platRepository = $entityManager->getRepository(Plat::class);
        // Si un identifiant est présent dans l'url alors il s'agit d'une modification
        // Dans le cas contraire il s'agit d'une création d'un plat
        if($id) {
            $mode = 'update';
            // On récupère le plat qui correspond à l'id passé dans l'url
            $plat = $platRepository->findBy(['id' => $id])[0];
        }
        else {
            $mode       = 'new';
            $plat    = new Plat();
        }
        $plat=$platRepository->findAll();
        // $categories = $entityManager->getRepository(Category::class)->findAll();
        $form = $this->createForm(PlatType::class, $plat);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $this->savePlat($plat, $doctrine,$mode);
            return $this->redirectToRoute('plat_edit', array('id' => $plat->getId()));
        }
        $parameters = array(
            'form'      => $form->createView(),
            'plat'   => $plat,
            'mode'      => $mode
        );
        return $this->render('plat/edit.html.twig', $parameters);
    }

    #[Route('/addPlat', name: 'plat_add')]
    private function savePlat(Plat $plat, ManagerRegistry $doctrine, string $mode){
        
        $entityManager = $doctrine->getManager();
        $entityManager->persist($plat);
        $entityManager->flush();
        $this->addFlash('success', 'Enregistré avec succès');
    }

    /**Suppression d'un plat */
    #[Route('/removePlat', name: 'plat_remove')]
    public function remove(ManagerRegistry $doctrine,PlatRepository $platRepository,int $id): Response
    {
        /// Entity Manager de Symfony
        $entityManager = $doctrine->getManager();
        $platRepository = $entityManager->getRepository(Plat::class);
        // On récupère l'article qui correspond à l'id passé dans l'URL
        $plat = $platRepository->findBy(['id' => $id])[0];
        // L'article est supprimé
        $entityManager->remove($plat);
        $entityManager->flush();
        return $this->redirectToRoute('app_home');
    }

}


