<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ManageTemplateController
{
    /**
     * @Route("/")
     */
    public function homepage() {
        return new Response('homepage template gallery');
    }
    /**
     * @Route("/admin", name="admin_home")
     */
    public function adminHome() {
        return new Response('Admin homepage template gallery');
    }
}