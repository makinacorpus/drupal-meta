<?php

namespace Meta\Input;

class TextField extends AbstractField
{
    protected function convertValue($item)
    {
        return $item['value'];
    }

    protected function getDatatype()
    {
        return t("text");
    }

    public function isFieldSupported($fieldName, $instance)
    {
        $field = field_info_field_by_id($instance['field_id']);

        switch ($field['type']) {

            case 'text':
            case 'text_long':
            case 'text_with_summary':
                return true;
        }

        return false;
    }
}
