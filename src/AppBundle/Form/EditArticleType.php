<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Input\EditArticle;
use Symfony\Component\Form\Extension\Core\Type;

/**
 * Description of EditArticleType
 *
 * @author rob
 */
class EditArticleType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('path', Type\TextType::class)
                ->add('title', Type\TextType::class)
                ->add('summary', Type\TextareaType::class)
                ->add('content', Type\TextareaType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => EditArticle::class,
        ));
    }

}
