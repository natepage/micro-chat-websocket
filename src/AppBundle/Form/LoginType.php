<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('_username', 'Symfony\Component\Form\Extension\Core\Type\TextType', array(
                'label' => 'Username',
                'label_attr' => array(
                    'class' => 'label-control col-md-2'
                ),
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('_password', 'Symfony\Component\Form\Extension\Core\Type\PasswordType', array(
                'label' => 'Password',
                'label_attr' => array(
                    'class' => 'label-control col-md-2'
                ),
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
        ;
    }

    public function getBlockPrefix()
    {
        return 'login_form';
    }
}