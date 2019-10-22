<?php


namespace App\EasyBundle\Form\Admin;

use App\EasyBundle\Entity\Post;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class PostForm extends BaseForm
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('mainImageFilename', FileType::class, [
            'mapped' => false,
            'attr' => ['type' => 'image'],
            'label' => 'crud.form.imageFilename.label',
            'required' => false,
            'constraints' => [
                new Image()
            ]
        ]);
    }
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
