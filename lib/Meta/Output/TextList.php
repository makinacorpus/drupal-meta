<?php

namespace Meta\Output;

use Meta\InputInterface;
use Meta\Node;
use Meta\OutputInterface;

/**
 * Uses text input and concat multiple values with a separator
 *
 * @todo Separator option
 */
class TextList extends Text
{
    public function getDefaultOptions()
    {
        return parent::getDefaultOptions() + array(
            'sep' => ', '
        );
    }

    public function buildNode($type, array $values, array $options = array())
    {
        $ret = array();

        foreach ($values as $index => $value) {
            if ($value = $this->getValue($type, $value, $options)) {
                $ret[] = $value;
            }
        }

        if (!empty($ret)) {
            $ret = array(new Node(implode($options['sep'], $ret)));
        }

        return $ret;
    }
}
