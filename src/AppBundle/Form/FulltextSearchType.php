<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FulltextSearchType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('parola', TextType::class, array(
            'attr' => array('placeholder' => 'Parola'),
            'label' => FALSE,
            'required' => FALSE,
        ))
        ->add('Cerca', SubmitType::class, array(
            'attr' => array('class' => 'btn-info btn')
        ));
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Node'
        ));
    }

    /**
     * @inheritDoc
     */
    public function getBlockPrefix()
    {
        return 'appbundle_node';
    }

}