<?php

namespace Meta\Plugin;

use Meta\AbstractComponent;
use Meta\PluginInterface;

abstract class AbstractPlugin extends AbstractComponent implements PluginInterface
{
    public function getMandatoryProperties()
    { 
        return array();
    }

    public function getDefaultMapping()
    {
        return array();
    }

    final public function getFieldName()
    {
        return 'meta_' . $this->getType();
    }

    final public function setMapping(array $mapping, $type, $bundle = null)
    {
        $instance = field_info_instance(
            $type,
            $this->getFieldName(),
            $bundle
        );

        if (!$instance) {
            trigger_error("Instance does not exist", E_USER_ERROR);
            return;
        }

        $instance['settings']['mapping'] = $mapping;
        field_update_instance($instance);
    }

    final public function getMapping($type, $bundle = null)
    {
        if ($bundle) {

            $instance = field_info_instance(
                $type,
                $this->getFieldName(),
                $bundle
            );

            if (!$instance) {
                trigger_error("Instance does not exist", E_USER_ERROR);
                // Fallback on field settings
            } else if (!empty($instance['settings']['mapping'])) {
                return $instance['settings']['mapping'];
            }
        }

        $field = field_info_field($this->getFieldName());

        if (!$field) {
            trigger_error("Field does not exist", E_USER_ERROR);
            // Fallback on plugin defaults
        } else if (!empty($field['settings']['mapping'])) {
            return $field['settings']['mapping'];
        }

        return $this->getDefaultMapping();
    }
}
