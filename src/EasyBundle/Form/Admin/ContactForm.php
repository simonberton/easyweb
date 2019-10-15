<?php


namespace App\EasyBundle\Form\Admin;

use App\EasyBundle\Entity\Contact;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactForm extends BaseForm
{
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
