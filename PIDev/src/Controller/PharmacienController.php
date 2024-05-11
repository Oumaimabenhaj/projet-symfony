<?php

namespace App\Controller;

use App\Entity\Pharmacien;
use App\Form\PharmacienType;
use App\Repository\PharmacienRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\AbstractType;
use App\Form\PharmacienTypeType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\GlobalUserRepository;





class PharmacienController extends AbstractController
{
    #[Route('/pharmacien', name: 'app_pharmacien')]
    public function index(): Response
    {
        return $this->render('pharmacien/index.html.twig', [
            'controller_name' => 'pharmacienController',
        ]);
    }

    #[Route('/addPharmacien', name: 'addPharmacien')]
    public function addPharmacien(Request $req, ManagerRegistry $doctrine): Response
    {
        $pharmacien = new Pharmacien();
        $form = $this->createForm(PharmacienType::class, $pharmacien);
    
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $cin = $pharmacien->getCin();
            
            // Vérifier si le cin existe déjà dans la base de données
            $existingPharmacien = $doctrine->getRepository(Pharmacien::class)->findOneBy(['cin' => $cin]);
            if ($existingPharmacien) {
                // Afficher un message d'erreur
                $form->get('cin')->addError(new FormError('Le CIN existe déjà.'));
                // Réafficher le formulaire avec le message d'erreur
                return $this->renderForm("admin/addadmin.html.twig", ["myForm" => $form]);
            }
            $imageFile = $form->get('image')->getData();

            if ($imageFile instanceof UploadedFile) {
                $newFilename = md5(uniqid()) . '.' . $imageFile->guessExtension();
                $imageFile->move($this->getParameter('image_directory'), $newFilename);
                $pharmacien->setImage($newFilename);
            }
            $password = $pharmacien->getPassword();
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $pharmacien->setPassword($hashedPassword);
            
            $em = $doctrine->getManager();
            $em->persist($pharmacien);
            $em->flush();
            
            // Rediriger vers une autre page après l'ajout réussi
            return $this->redirectToRoute('addPharmacien');
        }
        
        return $this->renderForm("pharmacien/addpharmacien.html.twig", ["myForm" => $form]);
    }
    

#[Route('/afficherPharmacien', name: 'app_afficherPharmacien')]
 public function affiche(Request $request,ManagerRegistry $doctrine,PharmacienRepository $PharmacienRepository): Response
{
 $searchQuery = $request->query->get('search', ''); 
$repository = $this->getDoctrine()->getRepository(Pharmacien::class); 
$listPharmacien = $searchQuery !== '' ?
        $PharmacienRepository->findBySearchQuery($searchQuery) : 
        $repository->findAll(); 
return $this->render('Pharmacien/consulterpharmacien.html.twig', [ 'listPharmacien' => $listPharmacien, 'searchQuery' => $searchQuery, ]);
 }

 #[Route('/editPharmacien/{id}', name: 'app_editPharmacien')]
 public function edit(PharmacienRepository $repository, $id, Request $request)
 {
     $pharmacien = $repository->find($id);
     $form = $this->createForm(PharmacienType::class, $pharmacien);
     $form->handleRequest($request);
     
     if ($form->isSubmitted() && $form->isValid()) {
         // Récupérer le nouveau mot de passe depuis le formulaire
         $newPassword = $form->get('password')->getData();
 
         // Vérifier si un nouveau mot de passe a été fourni
         if ($newPassword) {
             // Chiffrer le nouveau mot de passe
             $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
             $pharmacien->setPassword($hashedPassword);
         }
         $imageFile = $form->get('image')->getData();
 
         if ($imageFile instanceof UploadedFile) {
             $newFilename = md5(uniqid()) . '.' . $imageFile->guessExtension();
             $imageFile->move($this->getParameter('image_directory'), $newFilename);
             $pharmacien->setImage($newFilename);
         }
 
         $em = $this->getDoctrine()->getManager();
         $em->flush(); 
         
         return $this->redirectToRoute("app_afficherPharmacien");
     }
     
     return $this->render('pharmacien/editpharmacien.html.twig', [
         'myForm' => $form->createView(),
     ]);
 }
 
#[Route('/deletePharmacien/{id}', name: 'app_deletePharmacien')]
    public function delete($id, PharmacienRepository $repository)
    {
        $pharmacien = $repository->find($id);

        if (!$pharmacien) {
            throw $this->createNotFoundException('Pharmacien non trouvé');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($pharmacien);
        $em->flush();

        
        return $this->redirectToRoute('app_afficherPharmacien');
    }
    #[Route('/ShowPharmacien/{id}', name: 'app_showPharmacien')]
    public function showPharmacien($id, PharmacienRepository $repository)
    {
        $pharmacien = $repository->find($id);

        if (!$pharmacien) {
            return $this->redirectToRoute('app_afficherPharmacien');
        }
        return $this->render('pharmacien/detailspharmacien.html.twig',['pharmacien' =>$pharmacien]);
    }
    #[Route('/ShowPharmaciens', name: 'app_showPharmaciens')]
    public function showPharmaciens(PharmacienRepository $repository,SessionInterface $s,GlobalUserRepository $repo)
    {   $id = $s->get('id');
        $pharmacien = $repo->find($id);

        if (!$pharmacien) {
            return $this->redirectToRoute('app_afficherPharmacien');
        }
        return $this->render('pharmacien/editprofilepharmacien.html.twig',['pharmacien' =>$pharmacien]);
    }
    
    #[Route('/editPharmacienp', name: 'app_editPharmacienp')]
 public function editp(PharmacienRepository $repository, Request $request,SessionInterface $s)
 {
    
    $id = $s->get('id');
       
     $admin = $repository->find($id);
     $form = $this->createForm(PharmacienType::class, $admin);
     $form->handleRequest($request);
     
     if ($form->isSubmitted() && $form->isValid()) {
         // Récupérer le nouveau mot de passe depuis le formulaire
         $newPassword = $form->get('password')->getData();
 
         // Vérifier si un nouveau mot de passe a été fourni
         if ($newPassword) {
             // Chiffrer le nouveau mot de passe
             $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
             $admin->setPassword($hashedPassword);
         }
         $imageFile = $form->get('image')->getData();
 
         if ($imageFile instanceof UploadedFile) {
             $newFilename = md5(uniqid()) . '.' . $imageFile->guessExtension();
             $imageFile->move($this->getParameter('image_directory'), $newFilename);
             $admin->setImage($newFilename);
         }
 
         $em = $this->getDoctrine()->getManager();
         $em->flush(); 
         
         return $this->redirectToRoute("app_afficherPharmacien");
     }
     
     return $this->render('pharmacien/editprofilephar2.html.twig', [
         'myForm' => $form->createView(),
     ]);
 }
 

      
}
