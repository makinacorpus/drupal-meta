<?php

/**
 * @file
 * Meta administration pages.
 */

/**
 * Global settings form.
 */
function meta_admin_settings($form, &$form_state) {
  // @todo
  return system_settings_form($form);
}

/**
 * Bundle selection form.
 */
function meta_admin_bundle($form, &$form_state) {

  $entity_info = entity_get_info();

  $rows = array();
  foreach (meta_service_get()->getPluginInfo() as $key => $info) {

    if (!$bundles = fieldture_bundle_list('meta:' . $key)) {
      continue;
    }

    foreach ($bundles as $bundle_id) {
      list($type, $bundle) = explode(':', $bundle_id, 2);

      $options = array('query' => array('destination' => current_path()));
      $links = array(
        l(t("customize"), 'admin/config/services/meta/bundles/' . $key . '/' . $bundle_id, $options)
      );

      $rows[] = array(
        $info['label'],
        $entity_info[$type]['label'],
        $entity_info[$type]['bundles'][$bundle]['label'],
        implode(' / ', $links),
      );
    }
  }

  if (empty($rows)) {
    return $form;
  }

  $form['table'] = array(
    '#theme'  => 'table',
    '#rows'   => $rows,
    '#header' => array(
       t("Meta type"),
       t("Entity type"),
       t("Bundle"),
       '',
    ),
  );

  return $form;
}
