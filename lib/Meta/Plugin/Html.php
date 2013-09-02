<?php

namespace Meta\Plugin;

class Html extends AbstractPlugin
{
    public function allowsArbitrary()
    {
        return false;
    }

    public function getPropertyList()
    {
        return array(
            'description' => array(
                'label'  => t("Description"),
                'output' => array('text'),
             ),
            'keywords' => array(
                'label'  => t("Keywords"),
                'output' => array('html.keywords', 'textlist', 'text'),
             ),
            'author' => array(
                'label'  => t("Author"),
                'output' => array('username', 'user', 'text'),
            ),
            'copyright' => array(
                'label'  => t("Copyright"),
                'output' => array('username', 'user', 'text'),
            ),
        );
    }

    public function getDefaultMapping()
    {
        return array(
            array(
                'name'  => 'description',
                'input' => 'field-text:body',
            ),
            array(
                'name'  => 'author',
                'input' => 'uid:uid',
            ),
            array(
                'name'  => 'keywords',
                'input' => 'field-term:field_tags',
            ),
        );
    }
}
