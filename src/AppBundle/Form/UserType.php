<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Anysrv\RecaptchaBundle\Form\Type\AnysrvRecaptchaType;
use Anysrv\RecaptchaBundle\Validator\Constraints\IsTrue as RecaptchaTrue;

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
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $user = $event->getData();
            $form = $event->getForm();

            // check if the User object is "new"
            // If no data is passed to the form, the data is "null".
            // This should be considered a new "User"
            if (!$user || null === $user->getId()) {
                $form->add('recaptcha', AnysrvRecaptchaType::class, array(
                    'mapped' => false,
                    'constraints' => array(
                        new RecaptchaTrue(),
                    ),
                ));
            }
        });
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
