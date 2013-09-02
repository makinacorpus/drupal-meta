<?php

namespace Meta\Output;

use Meta\Node;

class Username extends AbstractOutput
{
    public function getDefaultOptions()
    {
        return array();
    }

    public function buildNode($type, array $values, array $options = array())
    {
        $ret = array();

        if ('user' === $type) {
            foreach ($values as $value) {
                if (is_object($value)) {
                    $ret[] = new Node(
                        strip_tags(
                            theme('username', array(
                                'account' => $value,
                            ))
                        )
                    );
                }
            }
        }

        return $ret;
    }
}
