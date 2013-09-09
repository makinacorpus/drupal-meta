<?php

namespace Meta\Entity\Bundle;

use Meta\AbstractComponent;
use Meta\Entity\BundleInterface;

class Page extends AbstractComponent implements BundleInterface
{
    public function getDescription()
    {
        return t("Arbitrary defaults that can be set on a per page basis. This also can be done using path matching patterns for site regions.");
    }

    public function getTargetFormElement($value = null, $readonly = false)
    {
        return array(
            '#type'          => 'textfield',
            '#title'         => t("Path"),
            '#size'          => 48,
            '#description'   => t("Drupal path this meta will apply onto."),
            '#default_value' => $value,
            '#readonly'      => $readonly,
            '#maxlength'     => 255,
        );
    }
}
