<?php

namespace App\Controller;

use App\Repository\EmploiRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CalendarController extends AbstractController
{
    #[Route('/calendar', name: 'app_calendar')]
    public function index(EmploiRepository $emploi): Response
    {      $events = $emploi->findAll();
           $rdvs = [];

           foreach($events as $event){
            $rdvs[] = [
                
                'start' => $event->getStart()->format('Y-m-d H:i:s'),
                'end' => $event->getEnd()->format('Y-m-d H:i:s'),
                'titre' => $event->getTitre(),
                'description' => $event->getDescription(),
                'id'=>$event->getId(),
                
            ];
        }

        $data = json_encode($rdvs);

        return $this->render('calendar/calendar.html.twig', compact('data'));
    }

       
    }

    























