<?php

namespace App\Form;

use App\Entity\Manager;
use App\Entity\Personal;
use App\Entity\Profile;
use App\Entity\Team;
use App\Entity\TeamMember;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username')
            ->add('email')
            ->add('password')
            ->add('created_at', null, [
                'widget' => 'single_text',
            ])
            ->add('entry_date', null, [
                'widget' => 'single_text',
            ])
            ->add('exit_date', null, [
                'widget' => 'single_text',
            ])
            ->add('matricule')
            ->add('department')
            ->add('name')
            ->add('firstConnexion', null, [
                'widget' => 'single_text',
            ])
            ->add('type_contract')
            ->add('status')
            ->add('SPC')
            ->add('teamMembers', EntityType::class, [
                'class' => TeamMember::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
            ->add('profile', EntityType::class, [
                'class' => Profile::class,
                'choice_label' => 'id',
            ])
            ->add('teams', EntityType::class, [
                'class' => Team::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
            ->add('manager', EntityType::class, [
                'class' => Manager::class,
                'choice_label' => 'id',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Personal::class,
        ]);
    }
}
