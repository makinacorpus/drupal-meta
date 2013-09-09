<?php

namespace Meta\Entity;

class Controller extends \DrupalDefaultEntityController
{
    protected function buildQuery(
        $ids,
        $conditions  = array(),
        $revision_id = false)
    {
        $query = db_select(
            $this->entityInfo['base table'],
            'base',
            array('fetch' => '\Meta\Entity\MetaEntity')
        );

        $query->addTag($this->entityType . '_load_multiple');

        $entity_fields = $this->entityInfo['schema_fields_sql']['base table'];
        $query->fields('base', $entity_fields);

        if ($ids) {
            $query->condition("base.{$this->idKey}", $ids, 'IN');
        }

        if (!empty($conditions)) {
            foreach ($conditions as $field => $value) {
                $query->condition('base.' . $field, $value);
            }
        }

        return $query;
    }

    public function load($ids = array(), $conditions = array())
    {
        $entities = parent::load($ids, $conditions);
        $service  = meta_service_get();

        foreach ($entities as $entity) {
            $entity->setService($service);
        }

        return $entities;
    }
}
