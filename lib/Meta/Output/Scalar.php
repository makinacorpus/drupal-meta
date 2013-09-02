<?php

namespace Meta\Output;

use Meta\InputInterface;
use Meta\Node;
use Meta\OutputInterface;

/**
 * Uses primitive values input and use it for display
 */
class Scalar implements OutputInterface
{
    public function getDefaultOptions()
    {
        return array();
    }

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
