<?php

namespace App\Controller;

use App\Entity\Rendezvous;
use App\Entity\Emploi;

use App\Form\EditRendezType;
use App\Form\RendezvousType;
use App\Repository\RendezvousRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;


class RendezvousController extends AbstractController
{
    #[Route('/rendezvous', name: 'app_rendezvous')]
    public function index(): Response
    {
        return $this->render('rendezvous/index.html.twig', [
            'controller_name' => 'RendezvousController',
        ]);
    }
    
#[Route('/addRendezvous', name: 'app_addRendezvous')]
public function addRendezvous(Request $request, ManagerRegistry $doctrine, SluggerInterface $slugger): Response
{   $rendezvous = new Rendezvous();
    $form = $this->createForm(RendezvousType::class, $rendezvous);
    $form->handleRequest($request);
    if ($form->isSubmitted() ) {
        $file = $form->get('file')->getData();
        if($file){ 
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();
            $file->move('C:\Users\laame\Desktop\abdslem\PIDev\public\uploads', $newFilename
            );
            $rendezvous->setFile($newFilename);}
            $em = $doctrine->getManager();
        //check if the rendez vous exists
            $existingRendezvous = $em->getRepository(Rendezvous::class)->findOneBy([
            'daterendezvous' => $rendezvous->getDaterendezvous(),
            'heurerendezvous' => $rendezvous->getHeurerendezvous(),
        ]);

        if ($existingRendezvous !== null) {
            $this->addFlash('error', 'Rendez-vous déjà existant.');
            return $this->redirectToRoute('app_addRendezvous');
   
}

        $em->persist($rendezvous);
        $em->flush();
        $this->addFlash('success', 'Rendez-vous ajouté avec succès.');

    }

    return $this->render('rendezvous/ajouterRdv/ajouterRdv.html.twig',  ['form' => $form->createView(),]);
}


    
   

    #[Route('/editRendezvous/{id}', name: 'app_editRendezvous')]
    public function editRendezvous(RendezvousRepository $repository, $id, Request $request,ManagerRegistry $manager)
    {
        $rendezvous = $repository->find($id);
        $form = $this->createForm(EditRendezType::class, $rendezvous);
        $form->handleRequest($request);
        $em = $manager->getManager();

        if ($form->isSubmitted()&& $form->isValid()) {
           
                $em->persist($rendezvous);
                $em->flush();
                $this->addFlash('success', 'Rendez-vous mis a jour avec succès.');
            }
            
        
    
        return $this->render('rendezvous/consulterRdv/modifierRdv.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/deleteRendezvous/{id}', name: 'app_deleteRendezvous')]
    public function deleteRendezvous($id, RendezvousRepository $repository,ManagerRegistry $manager)
    {
        $rendezVous = $repository->find($id);
        $em = $manager->getManager();
        $em->remove($rendezVous);
        $em->flush();
        $this->addFlash('success', 'Rendez vous annuler!');

       return $this->redirectToRoute('app_afficherRendezVous');

    }


    #[Route('/afficherRendezVous', name: 'app_afficherRendezVous')]

    public function afficherRendezVous(ManagerRegistry $doctrine):Response
    {

        $repository=$doctrine->getRepository(Rendezvous::class);
        $rendezvous=$repository->findAll();
        return $this->render('rendezvous/consulterRdv/afficherRdv.html.twig', ['list'=>$rendezvous]);
    }

    //Afficher rendez vous back office

    #[Route('/afficherRendezVousMedecin', name: 'app_afficherRendezVousMedecin')]

    public function afficherRendezVousMedcin(ManagerRegistry $doctrine):Response
    {
        $repository=$doctrine->getRepository(Rendezvous::class);
        $rendezvous=$repository->findAll();
        return $this->render('rendezvous/consulterRdv/afficherRdvMedecin.html.twig', ['list'=>$rendezvous]);
    }
    

   
    #[Route('/acceptRendezvous/{id}', name: 'app_acceptRendezvous')]
    public function acceptRendezvous($id, RendezvousRepository $repository, EntityManagerInterface $entityManager,ManagerRegistry $doctrine): Response
    {
        $rendezvous = $repository->find($id);
        if (!$rendezvous) {
            return new JsonResponse(['error' => 'Rendezvous not found'], Response::HTTP_NOT_FOUND);
        }
        
        $rendezvous->setEtat(true);
        return $this->redirectToRoute('app_afficherR');
    
    
    }
    #[Route('/afficherRendezVousAccepter', name: 'app_afficherR')]

    public function afficherRendezVousMedcinAceepte(ManagerRegistry $doctrine):Response
    {
        $repository=$doctrine->getRepository(Rendezvous::class);
        $rendezvous=$repository->findAll();
        return $this->render('rendezvous/consulterRdv/acceptedRendezvous.html.twig', ['list'=>$rendezvous]);
    }   

    #[Route('/afficherRendezVousAnnuler', name: 'app_afficherN')]
    public function afficherRendezVousMedcinAnuler(ManagerRegistry $doctrine):Response
    {
        $repository=$doctrine->getRepository(Rendezvous::class);
        $rendezvous=$repository->findAll();
        return $this->render('rendezvous/consulterRdv/rejectedRdv.html.twig', ['list'=>$rendezvous]);
    }
    
    #[Route('/rejectedRendezvous/{id}', name: 'app_rejectedRendezvous')]
    public function rejectedRendezvous($id, RendezvousRepository $repository, EntityManagerInterface $entityManager,ManagerRegistry $doctrine): Response
    {
        $rendezvous = $repository->find($id);
        if (!$rendezvous) {
            return new JsonResponse(['error' => 'Rendezvous not found'], Response::HTTP_NOT_FOUND);
        }
        
        $rendezvous->setEtat(false);
        return $this->redirectToRoute('app_afficherN');
    
    
    }
   
        
}