<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ManageTemplateController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     */
    public function homepage() {
        return new Response('homepage template gallery');
    }
    /**
     * @Route("/admin", name="admin_home")
     */
    public function adminHome() {
        return $this->render('manage/adminHome.html.twig',[
            'title'=>'Admin Home',
        ]);
    }
}