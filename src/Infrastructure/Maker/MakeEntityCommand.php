<?php 
namespace App\Infrastructure\Maker;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MakeEntityCommand extends AbstractMakeCommand {

    protected static $defaultName = 'next:entity';

    protected function configure(): void
    {
        $this
            ->setDescription('Crée une entité dans le domaine choisi')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $domain = $this->askDomain($io);    
        /** @var string $entity */
        $entity = $this->askEntity($io,$domain);
        /** @var Application $application */
        $application = $this->getApplication();
        $command = $application->find('make:entity');
        $arguments = [
            'command' => 'make:entity',
            'name' => "\\App\\Domain\\$domain\\Entity\\$entity",
        ];
        $greetInput = new ArrayInput($arguments);
        return $command->run($greetInput, $output);
    }




}


