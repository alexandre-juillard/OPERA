<?php

namespace App\Controller;

use DateInterval;
use App\Entity\Personal;
use App\Entity\Interview;
use App\Form\PersonalType;
use App\Event\PersonalCreatedEvent;
use App\Repository\PersonalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/personal')]
class PersonalController extends AbstractController
{
   
    private $em;
    private $eventDispatcher;
    
    public function __construct(EntityManagerInterface $em, EventDispatcherInterface $eventDispatcher)
    {
        $this->em = $em;
        $this->eventDispatcher = $eventDispatcher;
    }

    #[Route('/', name: 'app_personal_index', methods: ['GET'])]
    public function index(PersonalRepository $personalRepository): Response
    {
        return $this->render('personal/index.html.twig', [
            'personals' => $personalRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_personal_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $personal = new Personal();
        $form = $this->createForm(PersonalType::class, $personal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($personal);
            $this->em->flush();

            // $interview = new Interview;
            // $interview->setInterviewer($this->getUser())
            //             ->setStatus('Planifié')
            //             ->setTypeInterview('Entretien Annuel')
            //             ->setDate((new DateTime())->add(new DateInterval('P1M')));

            //  $entityManager->persist($interview);
            //  $entityManager->flush();

            $event = new PersonalCreatedEvent($personal);
            $this->eventDispatcher->dispatch($event, PersonalCreatedEvent::NAME);

            return $this->redirectToRoute('app_personal_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('personal/new.html.twig', [
            'personal' => $personal,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_personal_show', methods: ['GET'])]
    public function show(Personal $personal): Response
    {
        return $this->render('personal/show.html.twig', [
            'personal' => $personal,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_personal_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Personal $personal, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PersonalType::class, $personal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_personal_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('personal/edit.html.twig', [
            'personal' => $personal,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_personal_delete', methods: ['POST'])]
    public function delete(Request $request, Personal $personal, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$personal->getId(), $request->request->get('_token'))) {
            $entityManager->remove($personal);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_personal_index', [], Response::HTTP_SEE_OTHER);
    }
}
