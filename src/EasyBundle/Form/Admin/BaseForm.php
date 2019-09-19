<?php


namespace App\EasyBundle\Form\Admin;

use App\EasyBundle\Entity\BaseEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BaseForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'form.title.label',
                'required' => true,
                'attr' => [
                    'placeholder' => 'form.title.placeholder'
                ]
            ])
            ->add('description', TextType::class, [
                'label' => 'form.description.label',
                'required' => true,
                'attr' => [
                    'placeholder' => 'form.description.placeholder'
                ]
            ])
            ->add('content', TextareaType::class, [
                'label' => 'form.content.label',
                'required' => true,
                'attr' => [
                    'placeholder' => 'form.content.placeholder'
                ]
            ])
            ->add('slug', TextType::class, [
                'label' => 'form.slug.label',
                'help' => 'form.slug.help',
                'required' => false,
                'attr' => [
                    'placeholder' => 'form.slug.placeholder',
                ],
            ])
        ;

        $this->addPublishingFields($builder);

    }

    protected function addPublishingFields(FormBuilderInterface $builder)
    {
        $publishingOptions = [
            'publishing.publishStatus.published.label' => BaseEntity::STATUS_PUBLISHED,
            'publishing.publishStatus.unpublished.label' => BaseEntity::STATUS_UNPUBLISHED,
            'publishing.publishStatus.draft.label' => BaseEntity::STATUS_DRAFT
        ];

        $builder
            ->add('publishStatus', ChoiceType::class, [
                'translation_domain' => 'ws_cms_editorial',
                'label' => 'publishing.publishStatus.label',
                'choices' => $publishingOptions,
                'attr' => [
                    'data-component' => 'ws_select'
                ],
            ])
            ->add('publishSince', DateTimeType::class, [
                'label' => 'publishing.publishSince.label'
            ])
            ->add('publishUntil', DateTimeType::class, [
                'label' => 'publishing.publishUntil.label'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BaseEntity::class,
            'attr' => [
                'novalidate' => 'novalidate',
                'autocomplete' => 'off',
                'accept-charset'=> 'UTF-8'
            ]
        ]);
    }
}
