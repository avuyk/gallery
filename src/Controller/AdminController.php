<?php
namespace App\Controller;

use App\Entity\File;
use App\Repository\FileRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_home")
     */
    public function adminHome() {
        return $this->render('admin/adminHome.html.twig',[
            'title'=>'Admin Home',
        ]);
    }

    /**
     * @Route("/admin/file/new", name="add_file")
     */
    public function new(FileRepository $fileRepository)
    {
        $fileUpload = new File();
        $fileUpload->setFileName('name');

        $form= $this->createFormBuilder($fileUpload)
            ->add('fileName', TextType::class)
            ->add('tags', TextType::class)
            ->add('categories', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Upload File'])
            ->getForm();
        return $this->render('admin/adminFileNew.html.twig', [
            'form'=>$form->createView(),
                'title'=>'Upload a file',
        ]);
    }
}