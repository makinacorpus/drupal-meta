<?php

namespace Meta\Input;

class TermField extends AbstractField
{
    protected function convertValue($item)
    {
        if (!empty($item['tid'])) {
            return taxonomy_term_load($item['tid']);
        }
    }

    protected function getDatatype()
    {
        return t("term");
    }

    public function isFieldSupported($fieldName, $instance)
    {
        $field = field_info_field_by_id($instance['field_id']);

        switch ($field['type']) {

            case 'taxonomy_term_reference':
                return true;
        }

        return false;
    }
}
