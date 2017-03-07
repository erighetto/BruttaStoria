<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NodeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (in_array('ROLE_ADMIN', $options['role'])) {
            $builder
                ->add('title')
                ->add('slug')
                ->add('status')
                ->add('promote')
                ->add('sticky')
                ->add('updated')
                ->add('created');
        } else {
            $builder
            ->add('title');
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Node',
            'status' => false,
            'sticky' => false,
            'promote' => false,
            'role' => ['ROLE_USER'],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_node';
    }


}
