<?php

namespace Meta\Output;

use Meta\Node;

/**
 * Uses primitive values input and use it for display
 */
class Scalar extends AbstractOutput
{
    /**
     * Convert value to string
     *
     * @param null|scalar $value
     *   Value to convert
     *
     * @return string
     *   Value
     */
    public function convertValue($value)
    {
        switch (gettype($value)) {

            case "NULL":
                return "null";

            case "boolean":
                return $value ? "true" : "false";

            case "integer":
            case "double":
            case "string":
                return (string)$value;
        }
    }

    public function buildNode($type, array $values, array $options = array())
    {
        $ret = array();

        foreach ($values as $value) {
            if (!empty($value)) {
                if ($value = $this->convertValue($value)) {
                    $ret[] = new Node($value); 
                }
            }
        }

        return $ret;
    }
}
