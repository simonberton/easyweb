<?php


namespace App\EasyBundle\Form\Admin;

use App\EasyBundle\Entity\Post;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostForm extends BaseForm
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
            'attr' => [
                'novalidate' => 'novalidate',
                'autocomplete' => 'off',
                'accept-charset'=> 'UTF-8'
            ]
        ]);
    }
}
