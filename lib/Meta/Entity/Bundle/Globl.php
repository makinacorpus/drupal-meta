<?php

namespace Meta\Entity\Bundle;

use Meta\AbstractComponent;
use Meta\Entity\BundleInterface;

/**
 * "Global" is a reserverd keyword. "Globl" is fine, sorry.
 */
class Globl extends AbstractComponent implements BundleInterface
{
    public function getDescription()
    {
        return t("Global settings for site. There will be only one instance of this type, they are site defaults that will appear whenever no other meta tags are selected.");
    }

    public function getTargetFormElement($value = null, $readonly = false)
    {
        return array();
    }
}
