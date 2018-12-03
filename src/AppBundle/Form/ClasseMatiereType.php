<?php

namespace AppBundle\Form;
 
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClasseMatiereType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    
        $builder->add('coefficient')->add('matiere', EntityType::class, array(
        'class' => 'AppBundle\Entity\Matiere',
        'choice_label' => 'libelle',

    ));
    }
    /*
     ->add('classe', EntityType::class, array(
            'class' => 'AppBundle:Classe',
            'choice_label' => 'libelle',

        ))
     */
    
    /**
     * {@i
     * nheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\ClasseMatiere'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_classematiere';
    }


}
