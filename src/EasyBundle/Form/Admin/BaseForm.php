<?php


namespace App\EasyBundle\Form\Admin;

use App\EasyBundle\Entity\BaseEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BaseForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'crud.form.title.label',
                'required' => true,
                'attr' => [
                    'placeholder' => 'crud.form.title.placeholder'
                ]
            ])
            ->add('description', TextType::class, [
                'label' => 'crud.form.description.label',
                'required' => true,
                'attr' => [
                    'placeholder' => 'crud.form.description.placeholder'
                ]
            ])
            ->add('content', TextareaType::class, [
                'label' => 'crud.form.content.label',
                'required' => true,
                'attr' => [
                    'placeholder' => 'crud.form.content.placeholder'
                ]
            ])
            ->add('slug', TextType::class, [
                'label' => 'crud.form.slug.label',
                'help' => 'crud.form.slug.help',
                'required' => false,
                'attr' => [
                    'placeholder' => 'crud.form.slug.placeholder',
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
                'label' => 'publishing.publishStatus.label',
                'choices' => $publishingOptions,
                'attr' => [
                    'data-component' => 'easy_select'
                ],
            ])
            ->add('publishSince', TextType::class, [
                'label' => 'publishing.publishSince.label',
                'attr' => [
                    'class' => 'js-datePicker'
                ]
            ])
            ->add('publishUntil', TextType::class, [
                'label' => 'publishing.publishUntil.label',
                'attr' => [
                    'class' => 'js-datePicker'
                ]
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
