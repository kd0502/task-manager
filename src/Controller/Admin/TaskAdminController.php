<?php
namespace App\Controller\Admin;

use App\Entity\Task;
use App\Entity\User;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface as EM;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/tasks')]
class TaskAdminController extends AbstractController
{
    #[Route('', name: 'admin_tasks_index')]
    public function index(TaskRepository $repo)
    {
        return $this->render('admin/tasks/index.html.twig', [
            'tasks' => $repo->findBy([], ['createdAt' => 'DESC'])
        ]);
    }

    #[Route('/new/{user}', name: 'admin_tasks_new', defaults: ['user' => null])]
    public function new(?User $user, Request $req, EM $em)
    {
        $task = new Task();
        if ($user) { $task->setUser($user); }

        $form = $this->createForm(TaskType::class, $task)->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid() && $task->getUser()) {
            $em->persist($task); $em->flush();
            $this->addFlash('success', 'Task created.');
            return $this->redirectToRoute('admin_tasks_index');
        }

        return $this->render('admin/tasks/form.html.twig', ['form' => $form]);
    }

    #[Route('/{id}/edit', name: 'admin_tasks_edit')]
    public function edit(Task $task, Request $req, EM $em)
    {
        $form = $this->createForm(TaskType::class, $task)->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid() && $task->getUser()) {
            $em->flush();
            $this->addFlash('success', 'Task updated.');
            return $this->redirectToRoute('admin_tasks_index');
        }

        return $this->render('admin/tasks/form.html.twig', ['form' => $form]);
    }

    #[Route('/{id}/delete', name: 'admin_tasks_delete', methods: ['POST'])]
    public function delete(Task $task, EM $em)
    {
        $em->remove($task); $em->flush();
        $this->addFlash('success', 'Task deleted.');
        return $this->redirectToRoute('admin_tasks_index');
    }
}
