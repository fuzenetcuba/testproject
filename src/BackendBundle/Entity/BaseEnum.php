<?php

namespace BackendBundle\Entity;

/**
 * Base class to use on "enum" type clases in PHP5. Since PHP the enum type doesn't
 * exist
 *
 * To use just, inherit from this class en define your enum options as constants
 * of the class:
 *
 *      class MyEnum extends BaseEnum
 *      {
 *          const FIRST_OPTION = 1,
 *                SECOND_OPTION =2,
 *          ;
 *      }
 *
 * @package \BackendBundle\Entity
 */
class BaseEnum
{
    static public function toArray()
    {
        $reflection = new \ReflectionClass(get_called_class());
        return $reflection->getConstants();
    }

    static public function toChoices()
    {
        return array_map(sprintf('%s::%s', get_called_class(), 'alterKey'), array_flip(self::toArray()));
    }

    static public function getName($id)
    {
        $values = self::toChoices();

        if (null === $id || 0 === $id) {
            $id = 1;
        }

        return $values[(int)$id];
    }

    static private function alterKey($key)
    {
        return implode(' ', array_slice(explode('_', $key), 1));
    }

    static public function hasKey($key)
    {
        return array_key_exists($key, self::toArray());
    }

    static public function hasValue($key)
    {
        return array_key_exists($key, array_flip(self::toArray()));
    }
}
