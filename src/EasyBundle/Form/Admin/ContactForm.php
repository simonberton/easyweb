<?php


namespace App\EasyBundle\Form\Admin;

use App\EasyBundle\Entity\Contact;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;

class ContactForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'crud.form.name.label',
                'required' => false,
            ])
            ->add('email', TextType::class, [
                'label' => 'crud.form.email.label',
                'required' => false,
            ])
            ->add('message', TextareaType::class, [
                'label' => 'crud.form.message.label',
                'required' => false,
            ])
            ->add('createdAt', TextType::class, [
                'label' => 'crud.form.createdAt.label',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
            'attr' => [
                'novalidate' => 'novalidate',
                'autocomplete' => 'off',
                'accept-charset'=> 'UTF-8'
            ]
        ]);
    }
}
