<?php

namespace Meta\Output;

use Meta\AbstractComponent;
use Meta\OutputInterface;

abstract class AbstractOutput extends AbstractComponent implements OutputInterface
{
    public function getDefaultOptions()
    {
        return array();
    }
}
