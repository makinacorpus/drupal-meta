<?php

namespace Meta\Input;

use Meta\InputInterface;

/**
 * Fetch data from a field
 */
abstract class AbstractField implements InputInterface
{
    /**
     * Convert single item to targetted type
     *
     * @param array $item
     *   Drupal field item
     *
     * @return mixed
     *   Null values will be evicted
     */
    protected function convertValue($item)
    {
        return $item;
    }

    /**
     * Get field item values
     *
     * @param string $property
     *   Property name
     * @param string $type
     *   Entity type
     * @param object $entity
     *   Entity object
     *
     * @return mixed[]
     *   Values, null values will be evicted
     */
    final private function getItems($property, $type, $entity)
    {
        $ret = array();

        if (false !== ($items = field_get_items($type, $entity, $property))) {
            foreach ($items as $delta => $item) {
                if ($value = $this->convertValue($item)) {
                    $ret[] = $value;
                }
            }
        }

        return $ret;
    }

    final public function get($property, $type, $entity, $index = 0)
    {
        $items = $this->getItems($property, $type, $entity);

        if (isset($items[$index])) {
            return $items[$index];
        }
    }

    final public function getAll($property, $type, $entity)
    {
        return $this->getItems($property, $type, $entity);
    }

    /**
     * Tell if field is supported
     *
     * @param string $fieldName
     *   Drupal field name
     * @param string $instance
     *   Drupal field instanc
     *
     * @return boolean
     */
    protected function isFieldSupported($fieldName, $instance)
    {
        return true;
    }

    /**
     * Get output datatype label
     *
     * @return string
     */
    abstract protected function getDatatype();

    /**
     * Get label for the field
     *
     * @param string $fieldName
     * @param string $instance
     */
    protected function getLabelFor($fieldName, $instance)
    {
        return t("@field field (as @type)", array(
            '@field' => $instance['label'],
            '@type'  => $this->getDatatype(),
        ));
    }

    final public function findProperties($type, $bundle)
    {
        $ret = array();

        foreach (field_info_instances($type, $bundle) as $fieldName => $instance) {
            if ($this->isFieldSupported($fieldName, $instance)) {
                $ret[$fieldName] = $this->getLabelFor($fieldName, $instance);
            }
        }

        return $ret;
    }
}
