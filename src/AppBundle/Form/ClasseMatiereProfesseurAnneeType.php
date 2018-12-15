<?php

namespace AppBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClasseMatiereProfesseurAnneeType extends AbstractType
{
    protected  $user;
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->user = $options['user'];

        if ($options['nature'] == 'prof'){
            $builder
                ->add('classe_matiere',EntityType::class, [
                    'class' => 'AppBundle\Entity\ClasseMatiere',
                    'query_builder'=>function (EntityRepository $er/*,$user*/) {

                        return $er->createQueryBuilder('m')
                            ->leftJoin('m.matiere','mat')
                            ->leftJoin('mat.ecole','ec')
                            ->where('ec.id = 2')
//                            ->where('u.createdBy = :user')
//                            ->setParameter('user',$this->user )
//                            ->orderBy('u.nom', 'ASC')
                                ;
                    },
                    'label' => 'Selectionnez la matiere :',
                    'choice_label' => 'matiere.libelle',
                    'placeholder' => 'Sélectionnez la matiere',
                    'empty_data' => null,
                    'required' => true,
                    'attr' => array(
                        'class' => 'select2',
                        'required' => 'required',
                    )
                    ])
               // ->add('type_classe')
                //          ->add('annee')
            ;

        } elseif ($options['nature'] == 'matiere'){
            $builder
//            ->add('classe_matiere')
//            ->add('type_classe')
                ->add('professeur',EntityType::class ,[
                    'class' => 'AppBundle\Entity\Professeur',
                    'query_builder'=>function (EntityRepository $er/*,$user*/) {

                        return $er->createQueryBuilder('u')
                            ->where('u.createdBy = :user')
                            ->setParameter('user',$this->user )
                            ->orderBy('u.nom', 'ASC');
                    },
                    'label' => 'Professeur :',
                    'choice_label' => 'Identite',
                    'placeholder' => 'Sélectionnez un professeur',
                    'empty_data' => null,
                    'required' => true,
                    'attr' => array(
                        'class' => 'select2',
                        'required' => 'required',
                    )])
            ;
        }

//            ->add('annee')

    }/**
 * {@inheritdoc}
 */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\ClasseMatiereProfesseurAnnee',
            'user'=>['promoteur'],
            'nature'=>['prof']
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_classematiereprofesseurannee';
    }


}
