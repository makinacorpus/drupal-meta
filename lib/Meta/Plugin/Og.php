<?php

namespace Meta\Plugin;

class Og extends AbstractPlugin
{
    public function allowsArbitrary()
    {
        return false;
    }

    public function getPropertyList()
    {
        return array(
            // Common values.
            'og:type' => array(
                'label'  => t("Default: Type"),
                'output' => array('entitytype', 'text'),
            ),
            'og:title' => array(
                'label'  => t("Default: Title"),
                'output' => array('entitytype', 'text'),
            ),
            'og:description' => array(
                'label'  => t("Default: Description"),
                'output' => array('text'),
            ),
            'og:determiner' => array(
                'label'  => t("Default: Determiner"),
                'output' => array('og-determiner'),
            ),
            'og:url' => array(
                'label'  => t("Default: URL"),
                'output' => array('url'),
            ),
            // Contact information.
            'og:email' => array(
                'label'  => t("Contact: E-Mail"),
                'output' => array('mail', 'text'),
            ),
            'og:phone_number' => array(
                'label'  => t("Contact: Phone number"),
                'output' => array('phone', 'text'),
            ),
            'og:fax_number' => array(
                'label'  => t("Contact: Fax number"),
                'output' => array('phone', 'text'),
            ),
            // Medias.
            'og:audio' => array(
                'label'  => t("Media: Audio"),
                'output' => array('audio', 'file', 'url'),
            ),
            'og:image' => array(
                'label'  => t("Media: Image"),
                'output' => array('og-image'),
            ),
            'og:video' => array(
                'label'  => t("Media: Video"),
                'output' => array('video', 'file', 'url'),
            ),
            // Various.
            'og:author' => array(
                'label'  => t("Misc: Author"),
                'output' => array('username', 'text'),
            ),
            // Localisation.
            /*
            'og:latitude' => array(
                'label'  => t("Localisation: Latitude"),
                'output' => array(),
            ),
            'og:longitude' => array(
                'label'  => t("Localisation: Longitude"),
                'output' => array(),
            ),
            // Address.
            'og:street_address' => array(
                'label'  => t("Localisation address: Street"),
                'output' => array(),
            ),
            'og:locality' => array(
                'label'  => t("Localisation address: Locality"),
                'output' => array(),
            ),
            'og:region' => array(
                'label'  => t("Localisation address: Region"),
                'output' => array(),
            ),
            'og:postal-code' => array(
                'label'  => t("Localisation address: Postal code"),
                'output' => array(),
            ),
            'og:country-name' => array(
                'label'  => t("Localisation address: Country"),
                'output' => array(),
            ),
             */
        );
    }

    public function getMandatoryProperties()
    {
        return array(
            'og:type',
            'og:title',
            'og:url',
            'og:image',
        );
    }

    public function getDefaultMapping()
    {
        return array();
    }
}
