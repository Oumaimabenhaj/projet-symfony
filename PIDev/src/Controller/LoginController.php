<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\GlobalUser;
use App\Entity\Medecin;
use App\Entity\Patient;
use App\Entity\Pharmacien;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\LoginType;
use App\Repository\GlobalUserRepository;
use Doctrine\ORM\Mapping\Id;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class LoginController extends AbstractController{

    #[Route('/login', name: 'login')]
public function login(GlobalUserRepository $repository, Request $request, ManagerRegistry $doctrine , SessionInterface $s): Response
{
    $user = new GlobalUser();
    
    $login_form = $this->createForm(LoginType::class, $user);
    $login_form->handleRequest($request);
    
    if ($login_form->isSubmitted()) {
        $email = $user->getEmail();
        $password = $user->getPassword();

        $existingAdmin = $doctrine->getRepository(GlobalUser::class)->findOneBy(['email' => $email]);

        if ($existingAdmin) {
            // Vérification de l'accès interdit
            if ($existingAdmin->isInterlock() == 1) {
                // Message d'erreur pour l'accès interdit ou bloqué
                $login_form->addError(new FormError('Accès interdit ou bloqué.'));
            } elseif (password_verify($password, $existingAdmin->getPassword())) {
                $id = $existingAdmin->getId();
                $s->set('id', $id);
                
                if ($existingAdmin instanceof Admin) {
                    return $this->redirectToRoute("home_admin");
                } elseif ($existingAdmin instanceof Patient) {
                    return $this->redirectToRoute("app_afficherPatient");
                } elseif ($existingAdmin instanceof Medecin) {
                    return $this->redirectToRoute("app_afficherMedecin");
                } elseif ($existingAdmin instanceof Pharmacien) {
                    return $this->redirectToRoute("app_afficherPharmacien");
                }
            } else {
                // Erreur d'authentification
                $login_form->addError(new FormError('Adresse email ou mot de passe incorrect.'));
            }
        } else {
            // Erreur d'authentification
            $login_form->addError(new FormError('Adresse email ou mot de passe incorrect.'));
        }
    }
    
    return $this->renderForm("login/login.html.twig", ["login_form" => $login_form]);
}
    #[Route('/logout', name: 'app_logout')]
    public function logout()
    {
        return $this->redirectToRoute("app_home");
    }


    }

 
