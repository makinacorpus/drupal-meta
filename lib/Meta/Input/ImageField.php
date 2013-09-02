<?php

namespace Meta\Input;

class ImageField extends FileField
{
    protected function getDatatype()
    {
        return t("image");
    }

    public function isFieldSupported($fieldName, $instance)
    {
        $field = field_info_field_by_id($instance['field_id']);

        switch ($field['type']) {

            case 'image':
                return true;
        }

        return false;
    }
}
