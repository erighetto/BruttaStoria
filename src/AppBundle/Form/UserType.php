<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // dump($options['roles']);
        if (in_array('ROLE_ADMIN', $options['role'])) {
            $builder
                ->add('roleId')
                ->add('username')
                ->add('password')
                ->add('name')
                ->add('email')
                ->add('website')
                ->add('origin')
                ->add('image')
                ->add('bio')
                ->add('timezone')
                ->add('status')
                ->add('updated')
                ->add('created');
        } else {
            $builder
                ->add('username')
                ->add('name')
                ->add('email')
                ->add('website')
                ->add('origin')
                ->add('image')
                ->add('bio')
                ->add('timezone');
        }

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User',
            'validation_groups' => ['create'],
            'role' => ['ROLE_USER'],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_user';
    }


}
