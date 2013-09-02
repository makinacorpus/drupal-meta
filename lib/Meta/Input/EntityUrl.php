<?php

namespace Meta\Input;

class EntityUrl extends AbstractInput
{
    public function get($property, $type, $entity, $index = 0)
    {
        if (0 === $index) {
            if (($uri = entity_uri($type, $entity)) &&
                !empty($uri['path']))
            {
                return url($uri['path'], array(
                    'absolute' => true,
                ) + $uri);
            }
        }
    }

    public function getAll($property, $type, $entity)
    {
        return array($this->get($property, $type, $entity));
    }

    public function findProperties($type, $bundle)
    {
        $info = entity_get_info($type);

        if (isset($info['uri callback']) ||
            isset($info['bundle'][$bundle]['uri callback']))
        {
            return array(
                0 => t("Entity URL"),
            );
        }
    }
}
