<?php

namespace GabiU\JobeetBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use GabiU\JobeetBundle\Entity\Job;

class JobType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', 'choice', array("choices" => Job::getTypes()))
            ->add('company')
            ->add('logoFile', 'file', array("label" => "Company logo"))
            ->add('url')
            ->add('position')
            ->add('location')
            ->add('description','ckeditor')
            ->add('howToApply', 'ckeditor', array("label" => "How to apply"))
            ->add('isPublic', null, array("label" => "Public?", 'required' => false))
            ->add('email','email')
            ->add('category')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GabiU\JobeetBundle\Entity\Job'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'gabiu_jobeetbundle_job';
    }
}
