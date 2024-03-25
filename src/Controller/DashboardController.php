<?php

namespace App\Controller;

use App\Repository\GoalRepository;
use App\Repository\FeedbackRepository;
use App\Repository\TeamRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(GoalRepository $goalRepository, FeedbackRepository $feedbackRepository, TeamRepository $teamRepository): Response
    {
        // Je récupère les objectifs depuis la base de données
        $objectifs = $goalRepository->findBy([], ['deadline' => 'ASC']);

        // Je récupère les feedbacks depuis la base de données
        $feedbacks = $feedbackRepository->findAll();

        // Je récupère les informations sur les équipes depuis la base de données
        $equipes = $teamRepository->findAll();

        // Je retourne la vue du tableau de bord avec les données récupérées
        return $this->render('dashboard/dashboard.html.twig', [
            //Les variables $objectifs, $feedbacks, et $equipes sont destinées à stocker les données récupérées des repositories correspondants. 
            //Ces variables sont ensuite passées au template dashboard.html.twig pour afficher les données sur le tableau de bord.
            'objectifs' => $objectifs,
            'feedbacks' => $feedbacks,
            'equipes' => $equipes,
        ]);
    }
}

