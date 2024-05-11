<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\EmploiType;
use App\Entity\Emploi;
use App\Repository\EmploiRepository;

class EmploiController extends AbstractController
{
    #[Route('/emploi', name: 'app_emploi')]
    public function index(): Response
    {
        return $this->render('emploi/index.html.twig', [
            'controller_name' => 'EmploiController',
        ]);
    }
    #[Route('/addEmploi', name: 'app_addEmploi')]
    public function addEmploi(Request $request, ManagerRegistry $doctrine): Response
    {

        $emploi = new Emploi();
        $form = $this->createForm(EmploiType::class, $emploi);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            $em = $doctrine->getManager();
            //check if the rendez vous exists
            $existingEmplois = $em->getRepository(Emploi::class)->findOneBy([
                'start' => $emploi->getStart(),
                'end' => $emploi->getEnd(),
            ]);
    
            if ($existingEmplois !== null) {
                $this->addFlash('error', 'Rendez-vous déjà existant.');
                return $this->redirectToRoute('app_addRendezvous');
       
    }
    
            $em->persist($emploi);
            $em->flush();
            $this->addFlash('success', 'Emplois ajouté avec succès.');
            return $this->redirectToRoute('app_addEmploi');

        }
    
        return $this->renderForm("emploi/ajouterEmploi/ajouterEmploi.html.twig", ["form" => $form]);



}
#[Route('/editEmploi/{id}/edit', name: 'app_editEmploiiii', methods: ['PUT'])]
    public function editEmploiCalendar($id, Request $request, ManagerRegistry $manager): Response
    {
        $entityManager = $manager->getManager();
        $emploiRepository = $entityManager->getRepository(Emploi::class);

        // Find the Emploi entity by ID
        $emploi = $emploiRepository->find($id);

        if (!$emploi) {
            return new Response('Emploi not found', 404);
        }

        $data = json_decode($request->getContent(), true);

      
            $code = 200;

            $emploi->setTitre($data['titre']);
            $emploi->setStart(new \DateTime($data['start']));
            $emploi->setEnd(new \DateTime($data['end']));
            $emploi->setDescription($data['description']);

            // Update the database using Doctrine
            $entityManager->flush();

            return new Response('ok', $code);
        
    }
#[Route('/editEmploi/{id}', name: 'app_editEmploi')]
    public function editEmploi(EmploiRepository $repository, $id, Request $request,ManagerRegistry $manager)
    {
        $emploi = $repository->find($id);
        $form = $this->createForm(EmploiType::class, $emploi);
        $form->handleRequest($request);
        $em = $manager->getManager();

        if ($form->isSubmitted()&& $form->isValid()) {
           
                $em->persist($emploi);
                $em->flush();
                $this->addFlash('success', 'Emploi mis a jour avec succès.');
            }
            
        
    
        return $this->render('emploi/ajouterEmploi/editEmploi.html.twig', [
            'form' => $form->createView(),
            'emploi'=>$emploi,
        ]);
    }
    #[Route('/deleteEmploi/{id}', name: 'app_deleteEmploi')]
    public function deleteRendezvous($id, EmploiRepository $repository,ManagerRegistry $manager)
    {

        $emploi = $repository->find($id);
        $em = $manager->getManager();
        $em->remove($emploi);
        $em->flush();
        $this->addFlash('success', 'Rendez vous annuler!');

       return $this->redirectToRoute('app_afficherRendezVous');




    }

}