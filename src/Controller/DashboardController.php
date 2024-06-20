<?php

namespace App\Controller;

use App\Repository\GoalRepository;
use App\Repository\TeamRepository;
use Symfony\Component\Mercure\Update;
use App\Repository\FeedbackRepository;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(GoalRepository $goalRepository, FeedbackRepository $feedbackRepository, TeamRepository $teamRepository): Response
    {
        // Vérifier si l'utilisateur a le rôle de Manager
        $this->denyAccessUnlessGranted('ROLE_MANAGER');

        // Je récupère les objectifs depuis la base de données
        $objectifs = $goalRepository->findBy([], ['deadline' => 'ASC']);

        // Je récupère les feedbacks depuis la base de données
        $feedbacks = $feedbackRepository->findAll();

        // Je récupère les informations sur les équipes depuis la base de données
        $equipes = $teamRepository->findAll();

        // Je retourne la vue du tableau de bord avec les données récupérées
        return $this->render('dashboard/dashboard.html.twig', [
            'objectifs' => $objectifs,
            'feedbacks' => $feedbacks,
            'equipes' => $equipes,
        ]);
    }



    #[Route('/publish', name: 'publish')]

    public function publish(HubInterface $hub): Response

    {

        $update = new Update(
            'notifPasswordReset',
            json_encode(['status' => 'message envoyé'])

        );

        $hub->publish($update);
        return new Response('published!');
    }
}
