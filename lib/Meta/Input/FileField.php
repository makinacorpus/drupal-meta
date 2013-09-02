<?php

namespace Meta\Input;

class FileField extends AbstractField
{
    protected function convertValue($item)
    {
        if (isset($item['uri'])) {
            return (object)$item;
        }
    }

    protected function getDatatype()
    {
        return t("file");
    }

    public function isFieldSupported($fieldName, $instance)
    {
        $field = field_info_field_by_id($instance['field_id']);

        switch ($field['type']) {

            case 'file':
            case 'image':
                return true;
        }

        return false;
    }
}
