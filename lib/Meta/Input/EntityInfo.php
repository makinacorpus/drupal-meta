<?php

namespace Meta\Input;

class EntityInfo extends AbstractInput
{
    public function get($property, $type, $entity, $index = 0)
    {
        if (0 === $index) {

            $info = entity_get_info($type);
            list($id,, $bundle) = entity_extract_ids($type, $entity);

            return array(
                'bundle'       => $bundle,
                'bundle_label' => $info['bundles'][$bundle]['label'],
                'id'           => $id,
                'label'        => entity_label($type, $entity),
                'type'         => $type,
                'type_label'   => $info['label'],
            );
        }
    }

    public function getAll($property, $type, $entity)
    {
        return array($this->get($property, $type, $entity));
    }

    public function findProperties($type, $bundle)
    {
        return array(
            'bundle'       => t("Entity bundle"),
            'bundle_label' => t("Entity bundle label"),
            'id'           => t("Entity object identifier"),
            'label'        => t("Entity object label (title)"),
            'type'         => t("Entity type"),
            'type_label'   => t("Entity type label"),
        );
    }
}
