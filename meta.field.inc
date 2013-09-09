<?php

/**
 * @file
 * Meta field definition.
 */

/**
 * Implements hook_field_info().
 */
function meta_field_info() {
  return array(
    META_FIELD => array(
      'label'             => t("Meta information"),
      'description'       => t("Stores computed meta information."),
      'settings'          => array(),
      'instance_settings' => array(),
      'default_widget'    => 'meta_hidden',
      'default_formatter' => 'meta_hidden',
      'no_ui'             => true,
    ),
  );
}

/**
 * Implements hook_field_formatter_info().
 */
function meta_field_formatter_info() {
  return array(
    'meta_hidden' => array(
      'label'       => t("Hidden"),
      'description' => t("Meta data has no interest in being displayed."),
      'field types' => array(META_FIELD),
    ),
  );
}

/**
 * Implements hook_field_formatter_info().
 */
function meta_field_widget_info() {
  return array(
    'meta_hidden' => array(
      'label'       => t("Hidden"),
      'description' => t("Meta data is computed automatically."),
      'field types' => array(META_FIELD),
    ),
  );
}

/**
 * Implements hook_field_is_empty().
 */
function meta_field_is_empty($item, $field) {
  return empty($item['settings']) && empty($item['data']);
}
