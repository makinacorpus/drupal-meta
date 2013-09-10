<?php

namespace Meta\Plugin;

use Meta\AbstractComponent;
use Meta\PluginInterface;

abstract class AbstractPlugin extends AbstractComponent implements PluginInterface
{
    /**
     * Get field instance
     *
     * @return array
     *   Field instance if found null otherwise
     */
    final protected function getFieldInstance($type, $bundle)
    {
        return field_info_instance(
            $type,
            $this->getFieldName(),
            $bundle
        );
    }

    final public function allowsUserModifications($type, $bundle = null)
    {
       if ($bundle) {
            if ($instance = $this->getFieldInstance($type, $bundle)) {
                if (!empty($instance['settings']['manual'])) {
                    return (bool)$instance['settings']['manual'];
                }
            } else {
                trigger_error("Instance does not exist", E_USER_ERROR);
            }
        }
        // Allow fallback on field settings
        if ($field = field_info_field($this->getFieldName())) {
            if (!empty($field['settings']['manual'])) {
                return (bool)$field['settings']['manual'];
            }
        } else {
            trigger_error("Field does not exist", E_USER_ERROR);
        }
        // Fallback on plugin defaults in case of errors
        return false;
    }

    final public function toggleUserModifications($type, $bundle = null, $toggle = true)
    {
        if ($bundle) {
            if (!$instance = $this->getFieldInstance($type, $bundle)) {
                trigger_error("Instance does not exist", E_USER_ERROR);
            } else {
                $instance['settings']['manual'] = (bool)$toggle;
                field_update_instance($instance);
            }
        } else {
            if (!$field = field_info_field($this->getFieldName())) {
                trigger_error("Field does not exist", E_USER_ERROR);
            } else {
                $field['settings']['manual'] = (bool)$toggle;
                field_update_field($field);
            }
        }
    }

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
        if ($bundle) {
            if ($instance = $this->getFieldInstance($type, $bundle)) {
                $instance['settings']['mapping'] = $mapping;
                field_update_instance($instance);
            } else {
                trigger_error("Instance does not exist", E_USER_ERROR);
            }
        } else {
            if ($field = field_info_field($this->getFieldName())) {
                $field['settings']['mapping'] = $mapping;
                field_update_field($field);
            } else {
                trigger_error("Field does not exist", E_USER_ERROR);
            }
        }
    }

    final public function getMapping($type, $bundle = null)
    {
        if ($bundle) {
            if ($instance = $this->getFieldInstance($type, $bundle)) {
                if (!empty($instance['settings']['mapping'])) {
                    return $instance['settings']['mapping'];
                }
            } else {
                trigger_error("Instance does not exist", E_USER_ERROR);
            }
        }
        // Allow fallback on field settings
        if ($field = field_info_field($this->getFieldName())) {
            if (!empty($field['settings']['mapping'])) {
                return $field['settings']['mapping'];
            }
        } else {
            trigger_error("Field does not exist", E_USER_ERROR);
        }
        // Fallback on plugin defaults in case of errors
        return $this->getDefaultMapping();
    }
}
