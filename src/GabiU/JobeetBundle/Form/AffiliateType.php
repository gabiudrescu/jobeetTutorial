<?php

namespace GabiU\JobeetBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AffiliateType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('url')
            ->add('email')
            ->add('categories', null, array("expanded" => false, "multiple" => true))
            ->add("submit", "submit", array("label" => "Apply"))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GabiU\JobeetBundle\Entity\Affiliate'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'gabiu_jobeetbundle_affiliate';
    }
}
