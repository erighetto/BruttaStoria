<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Captcha\Bundle\CaptchaBundle\Form\Type\CaptchaType;

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
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
            ->add('created')
            ->add('captchaCode', CaptchaType::class, array(
                'captchaConfig' => 'UserCaptcha'
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User'
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
