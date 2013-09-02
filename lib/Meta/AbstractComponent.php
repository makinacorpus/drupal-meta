<?php

namespace Meta;

abstract class AbstractComponent extends ServiceAware implements ComponentInterface
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $label;

    final public function getLabel()
    {
        return $this->label;
    }

    final public function setLabel($label)
    {
        $this->label = $label;
    }

    final public function getType()
    {
        return $this->type;
    }

    final public function setType($type)
    {
        $this->type = $type;
    }
}
