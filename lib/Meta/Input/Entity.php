<?php

namespace Meta\Input;

class Entity extends AbstractInput
{
    public function get($property, $type, $entity, $index = 0)
    {
        if (0 === $index) {
            return $entity;
        }
    }

    public function getAll($property, $type, $entity)
    {
        return array($this->get($property, $type, $entity));
    }

    public function findProperties($type, $bundle)
    {
        return array('entity' => t("Entity object"));
    }
}
