<?php

namespace App\Controller;

use App\Entity\Medecin;
use App\Repository\MedecinRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\AbstractType;
use App\Form\MedecinType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\GlobalUserRepository;

class MedecinController extends AbstractController
{
    #[Route('/medecin', name: 'app_medecin')]
    public function index(): Response
    {
        return $this->render('medecin/index.html.twig', [
            'controller_name' => 'MedecinController',
        ]);
    }
   
    #[Route('/addMedecin', name: 'addMedecin')]
    public function addMedecin(Request $req, ManagerRegistry $doctrine): Response
{
    $medecin = new Medecin();
    $form = $this->createForm(MedecinType::class, $medecin);

    $form->handleRequest($req);
    if ($form->isSubmitted() && $form->isValid()) {
        $cin = $medecin->getCin();
        
        // Vérifier si le cin existe déjà dans la base de données
        $existingMedecin = $doctrine->getRepository(Medecin::class)->findOneBy(['cin' => $cin]);
        if ($existingMedecin) {
            $form->get('cin')->addError(new FormError('Le CIN existe déjà.'));
            return $this->renderForm("medecin/addmedecin.html.twig", ["myForm" => $form]);
        }
        
         $imageFile = $form->get('image')->getData();

         if ($imageFile instanceof UploadedFile) {
             $newFilename = md5(uniqid()) . '.' . $imageFile->guessExtension();
             $imageFile->move($this->getParameter('image_directory'), $newFilename);
             $medecin->setImage($newFilename);
         }
        $password = $medecin->getPassword();
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $medecin->setPassword($hashedPassword);
        
        $em = $doctrine->getManager();
        $em->persist($medecin);
        $em->flush();
        
        // Rediriger vers une autre page après l'ajout réussi
        return $this->redirectToRoute('addMedecin');
    }
    
    return $this->renderForm("medecin/addmedecin.html.twig", ["myForm" => $form]);
}

    #[Route('/afficherMedecin', name: 'app_afficherMedecin')]
 public function affiche(Request $request,ManagerRegistry $doctrine,MedecinRepository $MedecinRepository): Response
{
 $searchQuery = $request->query->get('search', ''); 
$repository = $this->getDoctrine()->getRepository(Medecin::class); 
$listMedecin = $searchQuery !== '' ?
        $MedecinRepository->findBySearchQuery($searchQuery) : 
        $repository->findAll(); 
return $this->render('medecin/consultermedecin.html.twig', [ 'listMedecin' => $listMedecin, 'searchQuery' => $searchQuery, ]);
 }
 

    
 #[Route('/editMedecin/{id}', name: 'app_editMedecin')]
 public function edit(MedecinRepository $repository, $id, Request $request)
 {
     $medecin = $repository->find($id);
     $form = $this->createForm(MedecinType::class, $medecin);
     $form->handleRequest($request);
     
     if ($form->isSubmitted() && $form->isValid()) {
         // Récupérer le nouveau mot de passe depuis le formulaire
         $newPassword = $form->get('password')->getData();
 
         // Vérifier si un nouveau mot de passe a été fourni
         if ($newPassword) {
             // Chiffrer le nouveau mot de passe
             $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
             $medecin->setPassword($hashedPassword);
         }
         $imageFile = $form->get('image')->getData();
 
         if ($imageFile instanceof UploadedFile) {
             $newFilename = md5(uniqid()) . '.' . $imageFile->guessExtension();
             $imageFile->move($this->getParameter('image_directory'), $newFilename);
             $medecin->setImage($newFilename);
         }
 
         $em = $this->getDoctrine()->getManager();
         $em->flush(); 
         
         return $this->redirectToRoute("app_afficherMedecin");
     }
     
     return $this->render('medecin/editmedecin.html.twig', [
         'myForm' => $form->createView(),
     ]);
 }
 
#[Route('/deleteMedecin/{id}', name: 'app_deleteMedecin')]
    public function delete($id, MedecinRepository $repository)
    {
        $medecin = $repository->find($id);

        if (!$medecin) {
            throw $this->createNotFoundException('Medecin non trouvé');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($medecin);
        $em->flush();

        
        return $this->redirectToRoute('app_afficherMedecin');
    }
    #[Route('/ShowMedecin/{id}', name: 'app_showMedecin')]
    public function showMedecin($id, MedecinRepository $repository)
    {
        $medecin = $repository->find($id);

        if (!$medecin) {
            return $this->redirectToRoute('app_afficherMedecin');
        }
        return $this->render('medecin/detailsmedecin.html.twig',['medecin' =>$medecin]);
    }
    #[Route('/ShowMedecins', name: 'app_showMedecins')]
    public function showAdmins(MedecinRepository $repository,SessionInterface $s,GlobalUserRepository $repo)
    {   $id = $s->get('id');
        $medecin = $repo->find($id);

        if (!$medecin) {
            return $this->redirectToRoute('app_afficherMedecin');
        }
        return $this->render('medecin/editprofilemedecin.html.twig',['medecin' =>$medecin]);
    }
    #[Route('/editMedecinp', name: 'app_editMedecinp')]
 public function editp(MedecinRepository $repository, Request $request,SessionInterface $s)
 {
    
    $id = $s->get('id');
       
     $medecin = $repository->find($id);
     $form = $this->createForm(MedecinType::class, $medecin);
     $form->handleRequest($request);
     
     if ($form->isSubmitted() && $form->isValid()) {
         // Récupérer le nouveau mot de passe depuis le formulaire
         $newPassword = $form->get('password')->getData();
 
         // Vérifier si un nouveau mot de passe a été fourni
         if ($newPassword) {
             // Chiffrer le nouveau mot de passe
             $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
             $medecin->setPassword($hashedPassword);
         }
         $imageFile = $form->get('image')->getData();
 
         if ($imageFile instanceof UploadedFile) {
             $newFilename = md5(uniqid()) . '.' . $imageFile->guessExtension();
             $imageFile->move($this->getParameter('image_directory'), $newFilename);
             $medecin->setImage($newFilename);
         }
 
         $em = $this->getDoctrine()->getManager();
         $em->flush(); 
         
         return $this->redirectToRoute("app_afficherMedecin");
     }
     
     return $this->render('medecin/editprofilemedecin2.html.twig', [
         'myForm' => $form->createView(),
     ]);
 }
    
}