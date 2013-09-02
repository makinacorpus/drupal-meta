<?php

namespace Meta\Input;

use Meta\InputInterface;

/**
 * Mock up implementation for testing
 *
 * For simple need you may consider using it yet it is not recommended
 */
class Property implements InputInterface
{
    public function get($property, $type, $entity, $index = 0)
    {
        if (isset($entity->{$property})) {
            if (is_array($entity->{$property})) {
                if (isset($entity->{$property}[$index])) {
                    return $entity->{$property}[$index];
                }
            } else if ($entity->{$property} instanceof \Traversable) {
                $i = 0;
                foreach ($entity->{$property} as $value) {
                    if ($i === $index) {
                        return $value;
                    }
                    ++$i;
                }
            }
        }
    }

    public function getAll($property, $type, $entity)
    {
        if (isset($entity->{$property})) {
            if (is_array($entity->{$property})) {
                return $entity->{$property};
            } else if ($entity->{$property} instanceof \Traversable) {
                return iterator_to_array($entity->{$property});
            }
        }
        return array();
    }

    public function findProperties($type, $bundle)
    {
        return array();
    }
}
