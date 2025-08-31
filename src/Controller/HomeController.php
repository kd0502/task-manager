<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('')]
class HomeController extends AbstractController
{
    #[Route('', name: 'main_index')]
    public function index()
    {
        return $this->redirectToRoute('admin_users_index');
    }
}