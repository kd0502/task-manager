<?php
namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'This email is already registered')]
class User
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Assert\Length(max: 180)]
    #[Assert\NotBlank(message: "Full name is required")]
    private string $name = '';

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank(message: "Email is required")]
    #[Assert\Email(message: "Please enter a valid email address")]
    private string $email = '';

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Task::class, cascade: ['remove'], orphanRemoval: true)]
    private Collection $tasks;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    /** @return Collection<int,Task> */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }
}
