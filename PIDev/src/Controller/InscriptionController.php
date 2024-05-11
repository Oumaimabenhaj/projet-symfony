<?php

namespace App\Controller;

use App\Entity\Patient;
use App\Form\PatientType;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\RegistrationType;
use Symfony\Component\Form\FormError;


use Symfony\Component\Form\FormInterface;

class InscriptionController extends AbstractController
{
    #[Route('/inscription', name: 'signin')]
    public function index(): Response
    {
        return $this->render('inscription/registration.html.twig', [
            'controller_name' => 'InscriptionController',
        ]);
    }
    /*
    #[Route('/user', name: 'app_user')]
    public function inscription(ManagerRegistry $doctrine, Request $request): Response
    {
        $user = new Patient();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $type = $form->get('Role')->getData();
            if ($type == 'patient')
            {
                $user = new Patient();
                $user->setScore(0);
            }
            else
            {
                $user = new Business();
                $user->setWebSite("");
            }
            
            $user->setNom($form->get('Name')->getData());
            $user->setPrenom($form->get('prenom')->getData());
            $user->setNumtel($form->get('Phone_Number')->getData());
            $user->setEmail($form->get('Email')->getData());
            $user->setPassword($form->get('password')->getData());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            

            return $this->redirectToRoute('home'); // Adjust the route name as needed
        }
        return $this->render('inscription/registration.html.twig', [
            'controller_name' => 'UserController',
            'f'=>$form->createView()
        ]);
    }

    */



    
/*
#[Route('/inscri', name: 'inscri')]
public function inscri(ManagerRegistry $doctrine, Request $request): Response
{
    $Patient = new Patient();
    $form = $this->createForm(RegistrationType::class, $Patient);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

        //$entityManager = $this->getDoctrine()->getManager();
        $entityManager = $doctrine->getManager();
        $entityManager->persist($Patient);
        $entityManager->flush();
        
        return $this->redirectToRoute('login'); // Adjust the route name as needed
    }
    //return $this->renderForm('inscription/registration.html.twig', ["myform"=>$form]);
    return $this->renderForm('inscription/registration.html.twig', ["form" => $form->createView()]);
}
*/


/*
#[Route('/inscri', name: 'inscri')]
public function inscri(ManagerRegistry $doctrine, Request $request): Response
{
    $Patient = new Patient();
    $form = $this->createForm(RegistrationType::class, $Patient);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

        $entityManager = $doctrine->getManager();
        $entityManager->persist($Patient);
        $entityManager->flush();
        
        return $this->redirectToRoute('login'); // Adjust the route name as needed
    }
    return $this->renderForm('inscription/registration.html.twig', ["form" => $form->createView()]);
}
*/


    
}
