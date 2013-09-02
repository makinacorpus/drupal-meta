<?php

namespace Meta\Output;

use Meta\Node;
use Meta\OutputInterface;

class EntityBundle implements OutputInterface
{
    public function getDefaultOptions()
    {
        return array();
    }

    public function buildNode($type, array $values, array $options = array())
    {
        $ret = array();

        if ('entityinfo' === $type) {
            foreach ($values as $value) {
                $ret[] = $value['bundle'];
            }
        }

        return $ret;
    }
}
