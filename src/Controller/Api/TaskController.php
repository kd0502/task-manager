<?php
namespace App\Controller\Api;

use App\Entity\Task;
use App\Entity\User;
use App\Enum\TaskStatus;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface as EM;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Http\JsonInput;

#[Route('/api')]
class TaskController extends AbstractController
{
    #[Route('/users/{id}/tasks', methods: ['POST'])]
    public function createForUser(int $id, Request $req, UserRepository $users, EM $em, ValidatorInterface $v): JsonResponse
    {
        $user = $users->find($id);
        if (!$user) {
            throw new NotFoundHttpException('User not found');
        }

        $data = JsonInput::body($req);
        $task = (new Task())
            ->setTitle((string)($data['title'] ?? ''))
            ->setDescription($data['description'] ?? null)
            ->setUser($user);

        $errors = $v->validate($task);
        if (count($errors) > 0) {
            return $this->json(['error' => (string)$errors], 422);
        }

        $em->persist($task);
        $em->flush();

        return $this->json([
            'id' => $task->getId(),
            'title' => $task->getTitle(),
            'status' => $task->getStatus()->value,
            'userId' => $user->getId(),
        ], 201);
    }

    #[Route('/users/{id}/tasks', methods: ['GET'])]
    public function listForUser(int $id, UserRepository $users): JsonResponse
    {
        $user = $users->find($id);
        if (!$user) {
            throw new NotFoundHttpException('User not found');
        }

        $tasks = $user->getTasks();
        $out = [];
        foreach ($tasks as $t) {
            $out[] = [
                'id' => $t->getId(),
                'title' => $t->getTitle(),
                'description' => $t->getDescription(),
                'status' => $t->getStatus()->value,
                'createdAt' => $t->getCreatedAt()->format(DATE_ATOM),
                'updatedAt' => $t->getUpdatedAt()->format(DATE_ATOM),
            ];
        }
        return $this->json($out);
    }

    #[Route('/tasks/{id}', methods: ['PATCH'])]
    public function updateStatus(int $id, Request $req, TaskRepository $tasks, EM $em): JsonResponse
    {
        $task = $tasks->find($id);
        if (!$task) {
            throw new NotFoundHttpException('Task not found');
        }

        $data = JsonInput::body($req);
        $new = $data['status'] ?? null;
        if (!in_array($new, array_column(TaskStatus::cases(), 'value'), true)) {
            return $this->json(['error' => 'Invalid status'], 422);
        }

        $task->markAs(TaskStatus::from($new));
        $em->flush();

        return $this->json(['id' => $task->getId(), 'status' => $task->getStatus()->value]);
    }

    #[Route('/tasks/{id}', methods: ['DELETE'])]
    public function delete(int $id, TaskRepository $tasks, EM $em): JsonResponse
    {
        $task = $tasks->find($id);
        if (!$task) {
            throw new NotFoundHttpException('Task not found');
        }
        $em->remove($task);
        $em->flush();

        return $this->json(null, 204);
    }
}
