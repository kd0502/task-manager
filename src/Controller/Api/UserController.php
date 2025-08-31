<?php
namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface as EM;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Http\JsonInput;

#[Route('/api/users')]
class UserController extends AbstractController
{
    #[Route('', methods: ['POST'])]
    public function create(Request $req, EM $em, ValidatorInterface $v): JsonResponse
    {
        $data = JsonInput::body($req);
        $user = (new User())
            ->setName((string)($data['name'] ?? ''))
            ->setEmail((string)($data['email'] ?? ''));

        $errors = $v->validate($user);
        if (count($errors) > 0) {
            return $this->json(['error' => (string)$errors], 422);
        }

        $em->persist($user);
        $em->flush();

        return $this->json(['id' => $user->getId(), 'name' => $user->getName(), 'email' => $user->getEmail()], 201);
    }

    #[Route('', methods: ['GET'])]
    public function list(UserRepository $repo): JsonResponse
    {
        $users = $repo->findAll();
        $out = array_map(fn(User $u) => [
        'id' => $u->getId(),
        'name' => $u->getName(),
        'email' => $u->getEmail(),
    ], $users);

        return $this->json($out);
    }
}
