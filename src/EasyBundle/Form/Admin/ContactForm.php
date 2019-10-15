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
                'label' => 'contact.form.email.label',
                'required' => false,
            ])
            ->add('email', TextType::class, [
                'label' => 'contact.form.description.label',
                'required' => false,
            ])
            ->add('message', TextareaType::class, [
                'label' => 'contact.form.message.label',
                'required' => false,
            ])
            ->add('createdAt', TextType::class, [
                'label' => 'contact.form.createdAt.label',
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
