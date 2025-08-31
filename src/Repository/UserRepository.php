<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Return all users ordered by name (useful for select lists).
     *
     * @return User[]
     */
    public function findAllOrderedByName(): array
    {
        return $this->createQueryBuilder('u')
            ->orderBy('u.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findOneByEmail(string $email): ?User
    {
        return $this->findOneBy(['email' => $email]);
    }

    /**
     * Simple name-based search.
     *
     * @return User[]
     */
    public function findByName(string $name): array
    {
        return $this->createQueryBuilder('u')
            ->where('u.name LIKE :name')
            ->setParameter('name', '%'.$name.'%')
            ->orderBy('u.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
