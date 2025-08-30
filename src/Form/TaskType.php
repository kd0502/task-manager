<?php
namespace App\Form;

use App\Entity\Task;
use App\Entity\User;
use App\Enum\TaskStatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $b, array $options): void
    {

        $b->add('title', TextType::class)
            ->add('description', TextareaType::class, ['required' => false])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'name',
                'placeholder' => 'Assign to User',
                'label' => 'Assigned User'
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'To do' => TaskStatus::TODO,
                    'In progress' => TaskStatus::IN_PROGRESS,
                    'Done' => TaskStatus::DONE
                ],
                'choice_value' => fn (?TaskStatus $s) => $s?->value,
                'choice_label' => fn (?TaskStatus $s) => match($s) {
                    TaskStatus::TODO => 'To do',
                    TaskStatus::IN_PROGRESS => 'In progress',
                    TaskStatus::DONE => 'Done',
                    default => ''
                }
            ]);
    }
}
