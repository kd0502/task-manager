<?php
namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface as EM;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/users')]
class UserAdminController extends AbstractController
{
    #[Route('', name: 'admin_users_index')]
    public function index(UserRepository $repo)
    {
        return $this->render('admin/users/index.html.twig', [
            'users' => $repo->findAll()
        ]);
    }

    #[Route('/new', name: 'admin_users_new')]
    public function new(Request $req, EM $em)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user)->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'User created.');
            return $this->redirectToRoute('admin_users_index');
        }

        return $this->render('admin/users/form.html.twig', ['form' => $form]);
    }

    #[Route('/{id}/edit', name: 'admin_users_edit')]
    public function edit(User $user, Request $req, EM $em)
    {
        $form = $this->createForm(UserType::class, $user)->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'User updated.');
            return $this->redirectToRoute('admin_users_index');
        }

        return $this->render('admin/users/form.html.twig', ['form' => $form]);
    }

    #[Route('/{id}/delete', name: 'admin_users_delete', methods: ['POST'])]
    public function delete(User $user, EM $em)
    {
        $em->remove($user); $em->flush();
        $this->addFlash('success', 'User deleted.');
        return $this->redirectToRoute('admin_users_index');
    }
}
