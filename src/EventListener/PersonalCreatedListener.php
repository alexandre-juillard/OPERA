<?php 

namespace App\EventListener;

use DateInterval;
use App\Entity\Interview;
use App\Event\PersonalCreatedEvent;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TypeInterviewRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PersonalCreatedListener implements EventSubscriberInterface
{
    private $em;
    private $security;
    private $typeInterviewRepo;

    public function __construct(EntityManagerInterface $em, Security $security, TypeInterviewRepository $typeInterviewRepo)
    {
        $this->em = $em;
        $this->security = $security;
        $this->typeInterviewRepo = $typeInterviewRepo;
    }

    public static function getSubscribedEvents()
    {
        return [
            PersonalCreatedEvent::NAME => 'onPersonalCreated',
        ];
    }

    public function onPersonalCreated(PersonalCreatedEvent $event)
    {
        $personal = $event->getPersonal();
        $typeContrat = $personal->getTypeContract();
        $interviewer = $this->security->getUser();

        $typesInterview = $this->typeInterviewRepo->findAll();



        foreach($typesInterview as $typeInterview){

            $name = $typeInterview->getName();
            $interval = $typeInterview->getIntervalDate();


            $interview = new Interview();
            $interview->setinterviewee($personal);
            $interview->setTypeInterview($typeInterview);
            $interview->setInterviewer($interviewer);
            $date = new \DateTime();
            $dateInterval = new DateInterval($interval);
            $date->add($dateInterval);
            $interview->setDate($date);

          

            $this->em->persist($interview);
            
        }
        
        $this->em->flush();
    }

    // private function getEntretiensByTypeContrat(string $typeContrat): array
    // {
    //     {
    //         if ($typeContrat === 'cdi') {
    //             return [
    //                 'entretien d\'entree' => '+0 month',
    //                 'Entretien OneToOne' => '+1 month',
    //                 'Entretien trimestriel' => '+3 month',
    //                 'Entretien Annuel' => '+1 year',
    //                 'Entretien Professionnel' => '+2 year',
    //                 'entretien fin pèriode d\'essai' => '+1 month'
    //             ];
    //         } elseif ($typeContrat === 'CDD') {
    //             return [
    //                 'entretien d\'entree' => '+0 month',
    //                 'Entretien OneToOne' => '+1 month',
    //                 'Entretien Mi-Parcours' => '+3 months',
    //                 'Entretien Fin de Contrat' => '+6 months',
    //             ];
    //         } elseif ($typeContrat === 'Alternance') {
    //             return [
    //                 'entretien d\'entree' => '+0 month',
    //                 'Entretien OneToOne' => '+1 month',
    //                 'Entretien Mi-Parcours' => '+1.5 months',
    //                 'Entretien Fin de Stage' => '+3 months',
    //             ];
    //         }
    //     }
    // }
    

}