<?php

namespace GabiU\JobeetBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CleanupCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('jobeet:cleanup')
            ->addArgument("days", InputArgument::OPTIONAL, "number of days since the job was created and it's inactive", 90)
            ->setDescription('Clean up jobs');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $repo = $this->getContainer()->get("doctrine.orm.entity_manager")->getRepository("GabiUJobeetBundle:Job");

        $days = $input->getArgument("days");
        $result = $repo->cleanup($days);

        $output->writeln(sprintf("Removed %d stale jobs", $result));
    }
}
