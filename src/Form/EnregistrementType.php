<?php

namespace App\Form;

use App\Entity\Enregistrement;
use App\Entity\Pays;
use App\Entity\Image;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EnregistrementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('dateNaiss', DateType::class, [
                // renders it as a single text box
                'widget' => 'single_text',
            ])
            ->add('lieuNaiss')
            ->add('statutSocial', ChoiceType::class, [
                'choices'  => [
                    'Celibataire' => 'Celibataire',
                    'Marié' => 'Marié',
                    'Divorcé' => 'Divorcé',
                    'Veuf(ve)' => 'Veuf(ve)',
                    'Autres' => 'Autres',
                ],
            ])
            ->add('profession', ChoiceType::class, [
                'choices'  => [
                    'Etudiant' => 'Etudiant',
                    'Eleve' => 'Eleve',
                    'Fonctionnaire' => 'Fonctionnaire',
                    'Retraité' => 'Retraité',
                    'Commercant' => 'Commercant',
                    'Chômeur' => 'Chômeur',
                    'Autres' => 'Autres',
                ],
            ])
            ->add('nomStructure')
            ->add('domaine')
            ->add('quartier')
            ->add('boursierSalarier', ChoiceType::class, [
                                        'choices'  => [
                                            'non' => false,
                                            'oui' => true,
                                        ],
                                    ])
            ->add('ville')
            ->add('contact')
            ->add('mail')
            ->add('pays', EntityType::class, [
                            // looks for choices from this entity
                            'class' => Pays::class,

                            // uses the User.username property as the visible option string
                            'choice_label' => 'nom',

                            // used to render a select box, check boxes or radios
                            // 'multiple' => true,
                            // 'expanded' => true,
                        ])
            ->add('image', ImageType::class)
            ->add('Enregistrer',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Enregistrement::class,
        ]);
    }
}
