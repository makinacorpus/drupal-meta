<?php

/**
 * @file
 * Meta hooks implementation.
 */

/**
 * Implements hook_meta_info().
 *
 * Provides a generic implementation for the user to be able to input anything.
 */
function meta_meta_info() {
  return array(
    'generic' => array(
      'label' => t("Generic"),
      'class' => '\Meta\Plugin\Generic',
    ),
  );
}

/**
 * Implements hook_meta_info_input().
 */
function meta_meta_info_input() {

  $info = array(
    'entity' => array(
      'class'  => '\Meta\Input\Entity',
      'output' => 'entity',
    ),
    'entityinfo' => array(
      'class'  => '\Meta\Input\EntityInfo',
      'output' => 'scalar',
    ),
    'entityurl' => array(
      'class'  => '\Meta\Input\EntityUrl',
      'output' => 'url',
    ),
    'field-file' => array(
      'class'  => '\Meta\Input\FileField',
      'output' => 'file',
    ),
    'field-image' => array(
      'class'  => '\Meta\Input\ImageField',
      'output' => 'image',
    ),
    'field-term' => array(
      'class'  => '\Meta\Input\TermField',
      'output' => 'term',
    ),
    'field-text' => array(
      'class'  => '\Meta\Input\TextField',
      'output' => 'text',
    ),
    'uid' => array(
      'class'  => '\Meta\Input\Uid',
      'output' => 'user',
    ),
  );

  foreach (array_keys(entity_get_info()) as $type) {
    $info['entity-' . $type] = array(
      'class'  => '\Meta\Input\Entity',
      'output' => array('entity', $type),
    );
  }

  return $info;
}

/**
 * Implements hook_meta_info_output().
 */
function meta_meta_info_output() {
  return array(
    'entitybundle' => array(
      'class' => '\Meta\Output\EntityBundle',
      'input' => array('scalar'),
    ),
    'entitytype' => array(
      'class' => '\Meta\Output\EntityType',
      'input' => array('scalar'),
    ),
    'html.keywords'  => array(
      'class' => '\Meta\Output\Html\Keywords',
      'input' => array('term', 'text'),
    ),
    'scalar'    => array(
      'class' => '\Meta\Output\Scalar',
    ),
    'structure' => array(
      'class' => '\Meta\Output\Structure',
    ),
    'text'      => array(
      'class' => '\Meta\Output\Text',
      'input' => array('text'),
    ),
    'textlist'  => array(
      'class' => '\Meta\Output\TextList',
      'input' => array('text'),
    ),
    'url'       => array(
      'class' => '\Meta\Output\Url',
      'input' => array('url', 'text', 'file', 'image'),
    ),
    'username'  => array(
      'class' => '\Meta\Output\Username',
      'input' => array('user', 'text'),
    ),
  );
}
