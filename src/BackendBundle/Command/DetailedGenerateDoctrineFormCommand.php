<?php

namespace BackendBundle\Command;

use Sensio\Bundle\GeneratorBundle\Command\GenerateDoctrineCommand;
use Sensio\Bundle\GeneratorBundle\Command\Validators;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DetailedGenerateDoctrineFormCommand extends GenerateDoctrineCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setDefinition(array(
                new InputArgument('entity', InputArgument::REQUIRED, 'The entity class name to initialize (shortcut notation)'),
            ))
            ->setDescription('Generates a form type class based on a Doctrine entity with specific field types')
            ->setHelp(<<<EOT
The <info>doctrine:generate:detailed:form</info> command generates a form class based on a Doctrine entity.

<info>php app/console doctrine:generate:detailed:form AcmeBlogBundle:Post</info>

Every generated file is based on a template. There are default templates but they can be overriden by placing custom templates in one of the following locations, by order of priority:

<info>BUNDLE_PATH/Resources/SensioGeneratorBundle/skeleton/form
APP_PATH/Resources/SensioGeneratorBundle/skeleton/form</info>

You can check https://github.com/sensio/SensioGeneratorBundle/tree/master/Resources/skeleton
in order to know the file structure of the skeleton
EOT
            )
            ->setName('doctrine:generate:detailed:form')
            ->setAliases(array('generate:detailed:form'))
        ;
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $entity = Validators::validateEntityName($input->getArgument('entity'));
        list($bundle, $entity) = $this->parseShortcutNotation($entity);

        $entityClass = $this->getContainer()->get('doctrine')->getAliasNamespace($bundle).'\\'.$entity;
        $metadata = $this->getEntityMetadata($entityClass);
        $bundle   = $this->getApplication()->getKernel()->getBundle($bundle);
        $generator = $this->getGenerator($bundle);

        $generator->generate($bundle, $entity, $metadata[0]);

        $output->writeln(sprintf(
            'The new %s.php class file has been created under %s.',
            $generator->getClassName(),
            $generator->getClassPath()
        ));
    }

    protected function createGenerator()
    {
        return new DetailedDoctrineFormGenerator($this->getContainer()->get('filesystem'));
    }
}
