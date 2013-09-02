<?php

namespace Meta\Output;

use Meta\Node;

/**
 * Display an URL
 */
class Url extends AbstractOutput
{
    public function getDefaultOptions()
    {
        return array();
    }

    public function buildNode($type, array $values, array $options = array())
    {
        $ret = array();

        foreach ($values as $value) {
            if (!empty($value)) {

                switch ($type) {

                    case 'url':
                    case 'text':
                        $value = (string)$value;
                        break;

                    case 'file':
                    case 'image':
                        $value = file_create_url($value->uri);
                        break;
                }

                if (!empty($value)) {
                    $ret[] = new Node($value);
                }
            }
        }

        return $ret;
    }
}
