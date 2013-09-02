<?php

namespace Meta\Output\Html;

use Meta\Output\TextList;

class Keywords extends TextList
{
    public function getDefaultOptions()
    {
        return array(
            'sep' => ', '
        );
    }

    protected function getValue($type, $value, array $options)
    {
        switch ($type) {

            case 'term':
                return $value->name;

            default:
                return (string)$value;
        }
    }
}
