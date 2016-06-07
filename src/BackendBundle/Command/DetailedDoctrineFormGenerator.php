<?php

namespace BackendBundle\Command;


use Sensio\Bundle\GeneratorBundle\Generator\Generator;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Doctrine\ORM\Mapping\ClassMetadataInfo;

class DetailedDoctrineFormGenerator extends Generator
{
    private $filesystem;
    private $className;
    private $classPath;

    /**
     * Constructor.
     *
     * @param Filesystem $filesystem A Filesystem instance
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function getClassName()
    {
        return $this->className;
    }

    public function getClassPath()
    {
        return $this->classPath;
    }

    /**
     * Generates the entity form class if it does not exist.
     *
     * @param BundleInterface $bundle The bundle in which to create the class
     * @param string $entity The entity relative class name
     * @param ClassMetadataInfo $metadata The entity metadata class
     */
    public function generate(BundleInterface $bundle, $entity, ClassMetadataInfo $metadata, $forceOverwrite = false)
    {
        $parts = explode('\\', $entity);
        $entityClass = array_pop($parts);

        $this->className = $entityClass . 'Type';
        $dirPath = $bundle->getPath() . '/Form';
        $this->classPath = $dirPath . '/' . str_replace('\\', '/', $entity) . 'Type.php';

        if (!$forceOverwrite && file_exists($this->classPath)) {
            throw new \RuntimeException(sprintf('Unable to generate the %s form class as it already exists under the %s file', $this->className, $this->classPath));
        }

        if (count($metadata->identifier) > 1) {
            throw new \RuntimeException('The form generator does not support entity classes with multiple primary keys.');
        }

        $parts = explode('\\', $entity);
        array_pop($parts);

        $fields_associations = $this->getFieldsFromMetadata($metadata);

//        echo var_dump($fields_associations[0]);
//        echo '-------------------------';
//        echo var_dump($fields_associations[1]);

        $this->renderFile('form/FormType.php.twig', $this->classPath, array(
            'fields' => $fields_associations[0],
            'associations' => $fields_associations[1],
            'namespace' => $bundle->getNamespace(),
            'entity_namespace' => implode('\\', $parts),
            'entity_class' => $entityClass,
            'bundle' => $bundle->getName(),
            'form_class' => $this->className,
            'form_type_name' => strtolower(str_replace('\\', '_', $bundle->getNamespace()) . ($parts ? '_' : '') . implode('_', $parts) . '_' . substr($this->className, 0, -4)),

            // Add 'setDefaultOptions' method with deprecated type hint, if the new 'configureOptions' isn't available.
            // Required as long as Symfony 2.6 is supported.
            'configure_options_available' => method_exists('Symfony\Component\Form\AbstractType', 'configureOptions'),
            'get_name_required' => !method_exists('Symfony\Component\Form\AbstractType', 'getBlockPrefix'),
        ));
    }

    /**
     * Returns an array of fields and association mappings. Fields can be both column fields and
     * association fields.
     *
     * @param  ClassMetadataInfo $metadata
     * @return array             $fields
     */
    private function getFieldsFromMetadata(ClassMetadataInfo $metadata)
    {
        // fields (with metadata) of the entity
        $fields = (array)$metadata->fieldMappings;

        // fields (with metadata) of the association mapping of the entity
        $association = $metadata->associationMappings;

        // Remove the primary key field if it's not managed manually
        if (!$metadata->isIdentifierNatural()) {
            foreach($metadata->identifier as $identifier)
                unset($fields[$identifier]);
        }

        foreach ($association as $fieldName => $relation) {
            if ($relation['type'] === ClassMetadataInfo::ONE_TO_MANY) {
                unset($association[$fieldName]);
            }
        }

        $result = array($fields, $association);

        return $result;
    }
}