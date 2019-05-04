<?php


namespace App\Form;


use App\Entity\Category;
use App\Entity\File;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class FileUploadFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('imageFile', FileType::class, [
                'mapped' => false,
                'constraints' => [
                    new Image([
                        'maxSize' => '5M'
                    ])
                ]
            ])
            ->add( 'filepath')
            ->add('description')
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'categoryName',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           'data_class' => File::class
        ]);
    }


}