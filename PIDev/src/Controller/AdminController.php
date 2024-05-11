<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\GlobalUser;
use App\Entity\Medecin;
use App\Entity\Patient;
use App\Entity\Pharmacien;
use App\Repository\AdminRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\AbstractType;
use App\Form\AdminType;
use App\Repository\GlobalUserRepository;
use App\Repository\MedecinRepository;
use App\Repository\MedicamentRepository;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use App\Repository\PatientRepository;
use App\Repository\PharmacienRepository;
use Doctrine\ORM\Mapping\Id;

class AdminController extends AbstractController
{    
    
    
    #[Route('/home', name: 'home_admin')]
    public function home(Request $request, GlobalUserRepository $GlobalUserRepository, AdminRepository $adminRepository, PatientRepository $patientRepository,PharmacienRepository $pharmacienRepository, MedecinRepository $medecinRepository): Response
    {
        $nbadmins = $adminRepository->countAllAdmin();
        $nbpatients = $patientRepository->countAllPatient();
        $nbpharmaciens = $pharmacienRepository->countAllPharmacien();
        $nbmedecins = $medecinRepository->countAllMedecin();
        $users = $medecinRepository->findAll();
        

        return $this->render('admin/home.html.twig', [
            'nbadmins' => $nbadmins,
            'nbpatients' => $nbpatients,
            'nbpharmaciens' => $nbpharmaciens,
            'nbmedecins' => $nbmedecins,
            'user' => $users,
            
        ]);
    }

    

    #[Route('/addAdmin', name: 'addAdmin')]
    public function addAdmin(Request $request, ManagerRegistry $doctrine): Response
    {
        $admin = new Admin();
        $form = $this->createForm(AdminType::class, $admin);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $cin = $admin->getCin();
            
            $existingAdmin = $doctrine->getRepository(Admin::class)->findOneBy(['cin' => $cin]);
            if ($existingAdmin) {
                $form->get('cin')->addError(new FormError('Le CIN existe déjà.'));
                return $this->renderForm("admin/addadmin.html.twig", ["myForm" => $form]);
            }
            
            $imageFile = $form->get('image')->getData();

            if ($imageFile instanceof UploadedFile) {
                $newFilename = md5(uniqid()) . '.' . $imageFile->guessExtension();
                $imageFile->move($this->getParameter('image_directory'), $newFilename);
                $admin->setImage($newFilename);
            }
            
            $password = $admin->getPassword();
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $admin->setPassword($hashedPassword);
            
            $em = $doctrine->getManager();
            $em->persist($admin);
            $em->flush();
            
            // Rediriger vers une autre page après l'ajout réussi
            return $this->redirectToRoute('addAdmin');
        }
        
        return $this->renderForm("admin/addadmin.html.twig", ["myForm" => $form]);
    }

    

#[Route('/afficherAdmin', name: 'app_afficherAdmin')]
 public function affiche(Request $request,ManagerRegistry $doctrine,AdminRepository $AdminRepository): Response
{
 $searchQuery = $request->query->get('search', ''); 
$repository = $this->getDoctrine()->getRepository(Admin::class); 
$listAdmin = $searchQuery !== '' ?
        $AdminRepository->findBySearchQuery($searchQuery) : 
        $repository->findAll(); 
return $this->render('Admin/consulteradmin.html.twig', [ 'listAdmin' => $listAdmin, 'searchQuery' => $searchQuery, ]);
 }
 
 #[Route('/editAdmin/{id}', name: 'app_editAdmin')]
public function edit(AdminRepository $repository, $id, Request $request)
{
    $admin = $repository->find($id);
    $form = $this->createForm(AdminType::class, $admin);
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
        
        return $this->redirectToRoute("app_afficherAdmin");
    }
    
    return $this->render('admin/editadmin.html.twig', [
        'myForm' => $form->createView(),
    ]);
}

 
#[Route('/deleteAdmin/{id}', name: 'app_deleteAdmin')]
    public function delete($id, AdminRepository $repository)
    {
        $admin = $repository->find($id);

        if (!$admin) {
            throw $this->createNotFoundException('Admin non trouvé');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($admin);
        $em->flush();

        
        return $this->redirectToRoute('app_afficherAdmin');
    }
    #[Route('/ShowAdmin/{id}', name: 'app_showAdmin')]
    public function showAdmin($id, AdminRepository $repository,SessionInterface $s)
    {   
        $admin = $repository->find($id);

        if (!$admin) {
            return $this->redirectToRoute('app_afficherAdmin');
        }
        return $this->render('admin/detailsadmin.html.twig',['admin' =>$admin]);
    }

    #[Route('/ShowAdmins', name: 'app_showAdmins')]
    public function showAdmins(AdminRepository $repository,SessionInterface $s,GlobalUserRepository $repo)
    {   $id = $s->get('id');
        $admin = $repo->find($id);

        if (!$admin) {
            return $this->redirectToRoute('app_afficherAdmin');
        }
        return $this->render('admin/editprofile.html.twig',['admin' =>$admin]);
    }

    
 #[Route('/editAdminp', name: 'app_editAdminp')]
 public function editp(AdminRepository $repository, Request $request,SessionInterface $s)
 {
    
    $id = $s->get('id');
       
     $admin = $repository->find($id);
     $form = $this->createForm(AdminType::class, $admin);
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
         
         return $this->redirectToRoute("app_afficherAdmin");
     }
     
     return $this->render('admin/editprofile2.html.twig', [
         'myForm' => $form->createView(),
     ]);
 }
 
  
}

      
