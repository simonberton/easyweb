<?php


namespace App\EasyBundle\Form\Admin;

use App\EasyBundle\Entity\Category;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryForm extends BaseForm
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
            'attr' => [
                'novalidate' => 'novalidate',
                'autocomplete' => 'off',
                'accept-charset'=> 'UTF-8'
            ]
        ]);
    }
}
