<?php


namespace App\Form;


use App\Entity\Category;
use App\Entity\ImageFile;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotNull;

class FileUploadFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var ImageFile|null $file */
        $file = $options['data'] ?? null;
        $isEdit = $file && $file->getId();

        $imageConstraints = [
            new Image([
                'maxSize' => '5M'
            ])
        ];
        if (!$isEdit || !$file->getImageFileName()) {
            $imageConstraints[] = new NotNull([
                'message' => 'Please upload an image',
            ]);
        }

        $builder
            ->add('imageFile', FileType::class, [
                'mapped' => false,
                'constraints' => $imageConstraints,
                'disabled' => $isEdit
            ])
            ->add('imageFileTitle', TextType::class, [
                'label' => 'Title',
                'required' => false,
                'attr' => [
                    'class' => 'js-set-title'
                 ],
            ])
            ->add('imageFileDescription', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
            ])
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'categoryName',
                'multiple' => 'true',
                'expanded' => 'true',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           'data_class' => ImageFile::class
        ]);
    }
}
