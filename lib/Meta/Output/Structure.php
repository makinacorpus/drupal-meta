<?php

namespace Meta\Output;

use Meta\OutputInterface;
use Meta\Node;

/**
 * Uses raw input and attempt to return it as complex and deep object structure
 */
class Structure extends Scalar
{
    public function getDefaultOptions()
    {
        return array();
    }

    protected function recursiveBuild($item, $prefix = null)
    {
        if (is_array($item) || $item instanceof \Traversable) {
            $ret = array();
            foreach ($item as $key => $value) {
                if (is_int($key)) {
                    $ret = array_merge($ret, $this->recursiveBuild($value, $prefix));
                } else {
                    $node = new Node(null, $prefix); // One too many
                    $node->addProperties($this->recursiveBuild($value, $key));
                    $ret[] = $node;
                }
            }
            if (!empty($ret)) {
                return $ret;
            }
        } else if ($value = $this->convertValue($item)) {
            return array(new Node($value, $prefix));
        } else {
            return array();
        }
    }

    public function buildNode($type, array $values, array $options = array())
    {
        $ret = array();

        foreach ($values as $value) {
            $ret = array_merge($ret, $this->recursiveBuild($value));
        }

        return $ret;
    }
}
