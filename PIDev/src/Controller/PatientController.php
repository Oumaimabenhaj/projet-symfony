<?php

namespace App\Controller;

use App\Entity\GlobalUser;
use App\Entity\Patient;
use App\Repository\PatientRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Form\PatientType;
use App\Form\RegistrationType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\GlobalUserRepository;




class PatientController extends AbstractController
{
    #[Route('/patient', name: 'app_patient')]
    public function index(): Response
    {
        return $this->render('patient/index.html.twig', [
            'controller_name' => 'PatientController',
        ]);
    }

    #[Route('/inscri', name: 'inscri')]
    public function inscri(Request $request, ManagerRegistry $doctrine): Response
    {
        $patient = new Patient();
        $form = $this->createForm(RegistrationType::class, $patient);
    
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $cin = $patient->getCin();
        
            $existingPatient = $doctrine->getRepository(Patient::class)->findOneBy(['cin' => $cin]);
            if ($existingPatient) {
                $form->get('cin')->addError(new FormError('Le CIN existe déjà.'));
                return $this->renderForm("inscription/registration.html.twig", ["form" => $form]);
            }
            // Handle image upload
            $imageFile = $form->get('image')->getData();

            if ($imageFile instanceof UploadedFile) {
                $newFilename = md5(uniqid()) . '.' . $imageFile->guessExtension();
                $imageFile->move($this->getParameter('image_directory'), $newFilename);
                $patient->setImage($newFilename);
            }

            // Crypter le mot de passe avant de le persister
            $password = $patient->getPassword();
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $patient->setPassword($hashedPassword);
            
            $patient->setInterlock(0);
            $entityManager = $doctrine->getManager();
            $entityManager->persist($patient);
            $entityManager->flush();
            
            // Rediriger vers une autre page après l'ajout réussi
            return $this->redirectToRoute('login');
        }
        
        return $this->renderForm("inscription/registration.html.twig", ["form" => $form]);
    }




    
#[Route('/addPatient', name: 'addPatient')]
public function addPatient(Request $req, ManagerRegistry $doctrine): Response
{
    $patient = new Patient();
    $form = $this->createForm(PatientType::class, $patient);

    $form->handleRequest($req);
    if ($form->isSubmitted() && $form->isValid()) {
       
        $cin = $patient->getCin();
        
        // Vérifier si le cin existe déjà dans la base de données
        $existingPatient = $doctrine->getRepository(Patient::class)->findOneBy(['cin' => $cin]);
        if ($existingPatient) {
            // Afficher un message d'erreur
            $form->get('cin')->addError(new FormError('Le CIN existe déjà.'));
            // Réafficher le formulaire avec le message d'erreur
            return $this->renderForm("patient/addpatient.html.twig", ["myForm" => $form]);
        }
        // Handle image upload
        $imageFile = $form->get('image')->getData();

        if ($imageFile instanceof UploadedFile) {
            $newFilename = md5(uniqid()) . '.' . $imageFile->guessExtension();
            $imageFile->move($this->getParameter('image_directory'), $newFilename);
            $patient->setImage($newFilename);
        }
        $password = $patient->getPassword();
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $patient->setPassword($hashedPassword);
      
        $em = $doctrine->getManager();
        $em->persist($patient);
        $em->flush();
        
        return $this->redirectToRoute('addPatient');
    }
    
    return $this->renderForm("patient/addpatient.html.twig", ["myForm" => $form]);
}
    


    #[Route('/afficherPatient', name: 'app_afficherPatient')]
 public function affiche(Request $request,ManagerRegistry $doctrine,PatientRepository $PatientRepository): Response
{
 $searchQuery = $request->query->get('search', ''); 
$repository = $this->getDoctrine()->getRepository(Patient::class); 
$listPatient = $searchQuery !== '' ?
        $PatientRepository->findBySearchQuery($searchQuery) : 
        $repository->findAll(); 
return $this->render('patient/consulterpatient.html.twig', [ 'listPatient' => $listPatient, 'searchQuery' => $searchQuery, ]);
 }
 


    
 #[Route('/editPatient/{id}', name: 'app_editpatient')]
 public function edit(PatientRepository $repository, $id, Request $request)
 {
     $patient = $repository->find($id);
     $form = $this->createForm(PatientType::class, $patient);
     $form->handleRequest($request);
     
     if ($form->isSubmitted() && $form->isValid()) {
         // Récupérer le nouveau mot de passe depuis le formulaire
         $newPassword = $form->get('password')->getData();
 
         // Vérifier si un nouveau mot de passe a été fourni
         if ($newPassword) {
             // Chiffrer le nouveau mot de passe
             $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
             $patient->setPassword($hashedPassword);
         }
         $imageFile = $form->get('image')->getData();
 
         if ($imageFile instanceof UploadedFile) {
             $newFilename = md5(uniqid()) . '.' . $imageFile->guessExtension();
             $imageFile->move($this->getParameter('image_directory'), $newFilename);
             $patient->setImage($newFilename);
         }
 
         $em = $this->getDoctrine()->getManager();
         $em->flush(); 
         
         return $this->redirectToRoute("app_afficherPatient");
     }
     
     return $this->render('patient/editpatient.html.twig', [
         'myForm' => $form->createView(),
     ]);
 }
 
#[Route('/deletePatient/{id}', name: 'app_deletePatient')]
    public function delete($id, PatientRepository $repository)
    {
        $patient = $repository->find($id);

        if (!$patient) {
            throw $this->createNotFoundException('patient non trouvé');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($patient);
        $em->flush();

        
        return $this->redirectToRoute('app_afficherPatient');
    }
    #[Route('/ShowPatient/{id}', name:'app_showPatient')]
    public function showPatient($id, PatientRepository $repository)
    {
        $patient = $repository->find($id);

        if (!$patient) {
            return $this->redirectToRoute('app_afficherPatient');
        }
        return $this->render('patient/detailspatient.html.twig',['patient' =>$patient]);
    }
    #[Route('/ShowPatients', name: 'app_showPatients')]
    public function showPatients(PatientRepository $repository,SessionInterface $s,GlobalUserRepository $repo)
    {   $id = $s->get('id');
        $patient = $repo->find($id);

        if (!$patient) {
            return $this->redirectToRoute('app_afficherPatient');
        }
        return $this->render('patient/editprofilepatient.html.twig',['patient' =>$patient]);
    }
    #[Route('/editPatientp', name: 'app_editPatientp')]
 public function editp(PatientRepository $repository, Request $request,SessionInterface $s)
 {
    
    $id = $s->get('id');
       
     $patient = $repository->find($id);
     $form = $this->createForm(PatientType::class, $patient);
     $form->handleRequest($request);
     
     if ($form->isSubmitted() && $form->isValid()) {
         // Récupérer le nouveau mot de passe depuis le formulaire
         $newPassword = $form->get('password')->getData();
 
         // Vérifier si un nouveau mot de passe a été fourni
         if ($newPassword) {
             // Chiffrer le nouveau mot de passe
             $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
             $patient->setPassword($hashedPassword);
         }
         $imageFile = $form->get('image')->getData();
 
         if ($imageFile instanceof UploadedFile) {
             $newFilename = md5(uniqid()) . '.' . $imageFile->guessExtension();
             $imageFile->move($this->getParameter('image_directory'), $newFilename);
             $patient->setImage($newFilename);
         }
 
         $em = $this->getDoctrine()->getManager();
         $em->flush(); 
         
         return $this->redirectToRoute("app_afficherPatient");
     }
     
     return $this->render('patient/editprofilepatient2.html.twig', [
         'myForm' => $form->createView(),
     ]);
 }
      
}



      

