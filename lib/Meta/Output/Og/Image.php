<?php

namespace Meta\Output\Og;

use Meta\Node;
use Meta\Output\AbstractOutput;

class Image extends AbstractOutput
{
    public function getDefaultOptions()
    {
        return array(
            'complete' => true,
        );
    }

    public function buildNode($type, array $values, array $options = array())
    {
        $ret = array();

        foreach ($values as $value) {
            if (!empty($value)) {
                switch ($type) {

                    case 'text':
                    case 'url':
                        $ret[] = new Node($value);
                        break;

                    case 'file':
                        $ret[] = new Node(file_create_url($value->uri));
                        break;

                    case 'image':
                        if ($options['complete'] && ($info = image_get_info($value->uri))) {
                            $node = new Node($value->uri);
                            $node->addProperties(array(
                                new Node($info['width'], 'width'),
                                new Node($info['height'], 'height'),
                                new Node($info['mime_type'], 'type'),
                            ));
                            $ret[] = $node;
                        } else {
                            $ret[] = new Node(file_create_url($value->uri));
                        }
                        break;
                }
            }
        }

        return $ret;
    }
}
