<?php

namespace BackendBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command to execute maintenance tasks over the deals collection, at the moment only
 * disables the expired deals on the database
 *
 * {@inheritDoc}
 */
class AppMaintainOpeningsCommand extends ContainerAwareCommand
{
    /**
     * {@inheritDoc}
     *
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function configure()
    {
        $this
            ->setName('app:maintain:openings')
            ->setDescription('Execute maintenance tasks on the existing Openings (change the Slug value [Business_ID-Position]');
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
        $output->writeln('<fg=green>EXECUTING:</> Updating opening slugs.');

        $records = $this->getContainer()->get('opening.manager')->updateSlugs();

        $output->writeln(sprintf('<fg=green>DONE:</> Updated <comment>%s Openings</comment> and <comment>%s Translation Slugs</comment>.',
            $records["openings"], $records["translations"]));
    }

}
