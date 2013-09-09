<?php

namespace Meta\Entity;

use Meta\ServiceAware;

/**
 * Drupal meta entity representation
 */
class MetaEntity extends ServiceAware
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $bundle;

    /**
     * @var string
     */
    public $target;

    /**
     * Get Drupal entity identifier for CRUD operations
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get bundle
     *
     * @return string
     */
    public function getBundle()
    {
        return $this->bundle;
    }

    /**
     * Get bundle instance
     *
     * @return BundleInterface
     */
    public function getBundleInstance()
    {
        return $this->getService()->getEntityBundle($this->bundle);
    }

    /**
     * Get target object identifier
     *
     * @return string
     */
    public function getTarget()
    {
        return $this->target;
    }
}
