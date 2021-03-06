<?php

namespace Meta\Input;

/**
 * Fetch a user identifier by arbitrarily looking up for a "uid" property
 */
class Uid extends AbstractInput
{
    public function get($property, $type, $entity, $index = 0)
    {
        if (0 === $index) {
            if (isset($entity->uid)) {
                if ("0" === $entity->uid || 0 === $entity->uid) {
                    return drupal_anonymous_user();
                } else if ($account = user_load($entity->uid)) {
                    return $account;
                }
            }
        }
    }

    public function getAll($property, $type, $entity)
    {
        if ($account = $this->get($property, $type, $entity)) {
            return array($account);
        }

        return array();
    }

    public function findProperties($type, $bundle)
    {
        return array(
            0 => t("Owner account"),
        );
    }
}
