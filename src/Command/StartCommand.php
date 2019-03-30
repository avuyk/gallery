<?php
declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StartCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('app:StartCommand')
            ->setDescription('Drop/Create Database and load Fixtures ....')
            ->setHelp('This command starts the app, it loads dummy data by recreating database, loading fixtures and starts the server to listen on localhost:8000. ');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $application = $this->getApplication();
        $application->setAutoExit(false);

        $output->writeln([
            '===================================================',
            '*********        Dropping DataBase        *********',
            '===================================================',
            '',
        ]);

        $options = array('command' => 'doctrine:database:drop',"--force" => true);
        $application->run(new \Symfony\Component\Console\Input\ArrayInput($options));


        $output->writeln([
            '===================================================',
            '*********        Deleting Old Files       *********',
            '===================================================',
            '',
        ]);

        $files = glob('public/images/gallery/*');
        foreach($files as $file){
            if(is_file($file))
                unlink($file);
        }


        $output->writeln([
            '===================================================',
            '*********        Creating DataBase        *********',
            '===================================================',
            '',
        ]);

        $options = array('command' => 'doctrine:database:create',"--if-not-exists" => true);
        $application->run(new \Symfony\Component\Console\Input\ArrayInput($options));

        $output->writeln([
            '===================================================',
            '*********        Migrating Database       *********',
            '===================================================',
            '',
        ]);

        $options = array('command' => 'doctrine:migrations:migrate',"-n" => true);
        $application->run(new \Symfony\Component\Console\Input\ArrayInput($options));

        $output->writeln([
            '===================================================',
            '*********         Loading Fixtures        *********',
            '===================================================',
            '',
        ]);

        $options = array('command' => 'doctrine:fixtures:load',"--no-interaction" => true);
        $application->run(new \Symfony\Component\Console\Input\ArrayInput($options));

        $output->writeln([
            '===================================================',
            '*********         Starting Server         *********',
            '===================================================',
            '',
        ]);

        $options = array('command' => 'server:run',"--no-interaction" => true);
        $application->run(new \Symfony\Component\Console\Input\ArrayInput($options));

    }
}