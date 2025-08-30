<?php
namespace App\Command;

use App\Enum\TaskStatus;
use App\Repository\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:tasks:report', description: 'Show number of tasks per status for each user')]
class TasksReportCommand extends Command
{
    public function __construct(private readonly UserRepository $users)
    {
        parent::__construct();
    }

protected function execute(InputInterface $input, OutputInterface $output): int
{
    foreach ($this->users->findAll() as $user) {
        $counts = [
            TaskStatus::TODO->value => 0,
                TaskStatus::IN_PROGRESS->value => 0,
                TaskStatus::DONE->value => 0,
            ];
            foreach ($user->getTasks() as $t) {
                $counts[$t->getStatus()->value] = ($counts[$t->getStatus()->value] ?? 0) + 1;
            }

            $output->writeln(sprintf("\nUser: %s", $user->getName()));
            $table = new Table($output);
            $table->setHeaders(['Status', 'Count'])
                ->setRows([
                    ['todo', $counts['todo']],
                    ['in_progress', $counts['in_progress']],
                    ['done', $counts['done']],
                ])->render();
        }

    return Command::SUCCESS;
}
}
