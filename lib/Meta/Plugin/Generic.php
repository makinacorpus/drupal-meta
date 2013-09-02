<?php

namespace Meta\Plugin;

class Generic extends AbstractPlugin
{
    public function allowsArbitrary()
    {
        return true;
    }

    public function getPropertyList()
    {
        return array();
    }
}
