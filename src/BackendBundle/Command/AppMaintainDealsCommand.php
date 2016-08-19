<?php

namespace BackendBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command to execute maintenance tasks over the deals collection, at the moment only
 * disables the expired deals on the database
 *
 * {@inheritDoc}
 */
class AppMaintainDealsCommand extends ContainerAwareCommand
{
    /**
     * {@inheritDoc}
     *
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
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

    /**
     * {@inheritDoc}
     *
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     * @throws \LogicException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
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
