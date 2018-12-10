<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CenseurType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ecole', EntityType::class ,[
                    'class' => 'AppBundle\Entity\Ecole',
                    'label' => 'Ecole :',
                    'choice_label' => 'nom',
                    'placeholder' => 'Sélectionnez son ecole',
                    'empty_data' => null,
                    'required' => true,
                    'attr' => array(
                        'class' => 'select2',
                        'required' => 'required',
                    )])
            ->add('nom')
            ->add("prenom")
            ->add('telephone')
            ->add("adresse")
            ->add('photo', FileType::class,
                array(
                    'required' => false,
                    'label' => "Photo du censeur (JPG/PNG/JPEG)",
                    "data_class" => null,
                ))
            ->add("username")
            ->add("email",EmailType::class,[
                'label' => "Adresse email *: ",
                'required' => true,
                'attr' => array(
                    'required' => 'required',
                    )
            ])
            ->add("password",RepeatedType::class,[
                'type' => PasswordType::class,
                'required' => true,
                'attr' => array(
                    'required' => 'required',
                ),
                'invalid_message' => 'Les mots de passe ne sont pas conformes.',
                'first_options' => array('label' => 'Mot de passe: * '),
                'second_options' => array('label' => 'Confirmer mot de passe: * '),
            ])

            ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Censeur'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_censeur';
    }


}
