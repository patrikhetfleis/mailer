<?php

namespace App\Form;

use App\Entity\Template;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TemplateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'subject',
                TextType::class,
                [
                    'label' => 'Předmět',
                ]
            )
            ->add(
                'body',
                CKEditorType::class,
                [
                    'label' => 'Text',
                    'config' => [
                        'entities_latin' => false,
                    ]
                ]
            )
            ->add(
                'label',
                TextType::class,
                [
                    'label' => 'Označení',
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Template::class,
        ]);
    }
}
