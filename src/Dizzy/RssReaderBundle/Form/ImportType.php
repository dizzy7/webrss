<?php

namespace Dizzy\RssReaderBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ImportType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', 'file', array('label' => 'Файл в формате OPML'));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'dizzy_rssreaderbundle_import';
    }
}
