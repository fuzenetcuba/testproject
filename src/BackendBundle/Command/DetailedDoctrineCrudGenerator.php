<?php

namespace BackendBundle\Command;


use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Sensio\Bundle\GeneratorBundle\Generator\DoctrineCrudGenerator;

class DetailedDoctrineCrudGenerator extends DoctrineCrudGenerator
{

    /**
     * Generates the routing configuration.
     *
     */
    protected function generateConfiguration()
    {
        if (!in_array($this->format, array('yml', 'xml', 'php'))) {
            return;
        }

        $target = sprintf(
            '%s/Resources/config/routing/%s.%s',
            $this->bundle->getPath(),
            strtolower(str_replace('\\', '_', $this->entity)),
            $this->format
        );

        $this->renderFile('crud/config/routing.'.$this->format.'.twig', $target, array(
            'actions'           => $this->actions,
            'route_prefix'      => $this->routePrefix,
            'route_name_prefix' => $this->routeNamePrefix,
            'bundle'            => $this->bundle->getName(),
            'entity'            => $this->entity,
            'identifier'        => $this->metadata->identifier[0],
        ));
    }

    /**
     * Generates the controller class only.
     *
     */
    protected function generateControllerClass($forceOverwrite)
    {
        $dir = $this->bundle->getPath();

        $parts = explode('\\', $this->entity);
        $entityClass = array_pop($parts);
        $entityNamespace = implode('\\', $parts);

        $target = sprintf(
            '%s/Controller/%s/%sController.php',
            $dir,
            str_replace('\\', '/', $entityNamespace),
            $entityClass
        );

        if (!$forceOverwrite && file_exists($target)) {
            throw new \RuntimeException('Unable to generate the controller as it already exists.');
        }

        $this->renderFile('crud/controller.php.twig', $target, array(
            'actions'           => $this->actions,
            'route_prefix'      => $this->routePrefix,
            'route_name_prefix' => $this->routeNamePrefix,
            'bundle'            => $this->bundle->getName(),
            'entity'            => $this->entity,
            'entity_singularized' => $this->entitySingularized,
            'entity_pluralized' => $this->entityPluralized,
            'identifier'        => $this->metadata->identifier[0],
            'entity_class'      => $entityClass,
            'namespace'         => $this->bundle->getNamespace(),
            'entity_namespace'  => $entityNamespace,
            'format'            => $this->format,
        ));
    }

    /**
     * Generates the new.html.twig template in the final bundle.
     *
     * @param string $dir The path to the folder that hosts templates in the bundle
     */
    protected function generateNewView($dir)
    {
        $fields_associations = $this->getFieldsFromMetadata($this->metadata);
        $this->renderFile('crud/views/new.html.twig.twig', $dir.'/new.html.twig', array(
            'bundle'            => $this->bundle->getName(),
            'entity'            => $this->entity,
            'entity_singularized' => $this->entitySingularized,
            'identifier'        => $this->metadata->identifier[0],
            'fields'            => $fields_associations[0],
            'associations'      => $fields_associations[1],
            'route_prefix'      => $this->routePrefix,
            'route_name_prefix' => $this->routeNamePrefix,
            'actions'           => $this->actions,
        ));
    }

    /**
     * Generates the edit.html.twig template in the final bundle.
     *
     * @param string $dir The path to the folder that hosts templates in the bundle
     */
    protected function generateEditView($dir)
    {
        $fields_associations = $this->getFieldsFromMetadata($this->metadata);
        $this->renderFile('crud/views/edit.html.twig.twig', $dir.'/edit.html.twig', array(
            'bundle'            => $this->bundle->getName(),
            'entity'            => $this->entity,
            'entity_singularized' => $this->entitySingularized,
            'identifier'        => $this->metadata->identifier[0],
            'fields'            => $fields_associations[0],
            'associations'      => $fields_associations[1],
            'route_prefix'      => $this->routePrefix,
            'route_name_prefix' => $this->routeNamePrefix,
            'actions'           => $this->actions,
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