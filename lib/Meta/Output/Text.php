<?php

namespace Meta\Output;

use Meta\InputInterface;
use Meta\Node;
use Meta\OutputInterface;

/**
 * Uses text input and use it for display
 *
 * @todo Truncate option
 */
class Text implements OutputInterface
{
    public function getDefaultOptions()
    {
        return array(
            'truncate' => 255,
            'format'   => META_FILTER,
        );
    }

    /**
     * Get value from value
     *
     * @param string $type
     *   Input type
     * @param mixed $value
     *   Input value
     *
     * @return string
     */
    protected function getValue($type, $value, array $options)
    {
        if ($value = (string)$value) {
            if ($options['format']) {
                $value = check_markup($value, $options['format']);
            }
            if ($options['truncate'] && $options['truncate'] < strlen($value)) {
                $value = text_summary($value, $options['format'], $options['truncate']);
            }
            return $value;
        }
    }

    public function buildNode($type, array $values, array $options = array())
    {
        $ret = array();

        foreach ($values as $value) {
            if ($value = $this->getValue($type, $value, $options)) {
                $ret[] = new Node($value);
            }
        }

        return $ret;
    }
}
