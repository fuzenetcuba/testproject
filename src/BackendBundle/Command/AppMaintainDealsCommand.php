<?php

namespace BackendBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AppMaintainDealsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:maintain:deals')
            ->setDescription('Execute maintenance tasks on the existing Deals (remove expired deals)')
            ->addArgument('date',
                InputArgument::OPTIONAL,
                'Valid DateTime expression to be used as starting point'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $date = $input->getArgument('date');

        if (null !== $date) {
            $date = new \DateTime($date);
        } else {
            $date = new \DateTime();
        }

        $output->writeln(sprintf('<fg=red>DISABLING</> expired deals until <comment>%s</comment>', $date->format('d.m.Y H:i:s')));

        $this->getContainer()->get('deal.manager')->disableExpiredDeals();

        $output->writeln('done.');
    }

}
