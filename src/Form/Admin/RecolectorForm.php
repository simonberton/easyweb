<?php


namespace App\Form\Admin;

use App\EasyBundle\Form\Admin\BaseForm;
use App\Entity\User;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecolectorForm extends BaseForm
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'attr' => [
                'novalidate' => 'novalidate',
                'autocomplete' => 'off',
                'accept-charset'=> 'UTF-8'
            ]
        ]);
    }
}

    