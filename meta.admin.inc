<?php

/**
 * @file
 * Meta administration pages.
 */

use Meta\Entity\BundleInterface;
use Meta\Entity\MetaEntity;

/**
 * Global settings form.
 */
function meta_admin_settings($form, &$form_state) {
  // @todo
  return system_settings_form($form);
}

/**
 * Misc. types (non entity) overview.
 */
function meta_admin_misc() {

  $build  = array();
  $rows   = array();
  $header = array(
    t("Bundle"),
    t("Name"),
    t("Description"),
    t("Count"),
    '',
  );

  $service = meta_service_get();

  foreach ($service->getEntityBundleInfo() as $key => $info) {
    if ($bundle = $service->getEntityBundle($key)) {
      $row = array();

      $row[] = $bundle->getType();
      $row[] = $bundle->getLabel();
      $row[] = $bundle->getDescription();
      $row[] = (int)db_query("SELECT COUNT(*) FROM {meta_misc} WHERE bundle = :bundle", array(':bundle' => $bundle->getType()))->fetchField();

      $links   = array();
      $links[] = l("Add", 'admin/config/services/meta/misc/add/' . $bundle->getType());

      $row[] = implode(' / ', $links);

      $rows[] = $row;
    }
  }

  if (empty($rows)) {
    $rows[] = array(array('data' => t("There is no bundle to display."), 'colspan' => count($header)));
  }

  $build['table'] = array(
    '#theme'  => 'table',
    '#header' => $header,
    '#rows'   => $rows,
  );

  return $build;
}

/**
 * Misc. listing.
 */
function meta_admin_misc_list($form, &$form_state) {

  $service = meta_service_get();

  $rows   = array();
  $header = array(
    'id' => array(
      'data'  => t("Id."),
      'field' => 'm.id',
    ),
    'bundle' => array(
      'data'  => t("Type"),
      'field' => 'm.bundle',
    ),
    'target' => array(
      'data'  => t("Target"),
      'field' => 'm.value',
    ),
    'info' => '',
  );

  // @todo Operation form
  // @todo Filter form (GET)

  $q = db_select('meta_misc', 'm')
    ->fields('m')
    ->extend('TableSort')
    ->orderByHeader($header);
  $idList = $q
    ->execute()
    ->fetchCol();

  foreach ($service->loadEntities($idList) as $entity) {

    $row = array(
      'id'     => $entity->getId(),
      'bundle' => $entity->getBundleInstance()->getLabel(),
      'target' => check_plain($entity->getTarget()),
    );

    $links   = array();
    $base    = 'admin/config/services/meta/misc/list/' . $entity->getId();
    $options = array('query' => array('destination' => current_path()));
    $links[] = l(t("edit"), $base, $options);
    $links[] = l(t("delete"), $base . '/delete', $options);
    $row['info'] = implode(' / ', $links);

    $rows[$entity->getId()] = $row;
  }

  $form['selected'] = array(
    '#type'    => 'tableselect',
    '#header'  => $header,
    '#options' => $rows,
    '#empty'   => t("No content to display."),
  );

  $form['actions'] = array(
    '#type'  => 'actions',
    'delete' => array(
      '#type'  => 'submit',
      '#value' => t("Delete selected"), // @todo
    ),
  );

  return $form;
}

/**
 * Add new meta entity form.
 */
function meta_admin_misc_add($form, &$form_state, BundleInterface $bundle) {

  $form['#bundle'] = $bundle;

  $form['value'] = $bundle->getTargetFormElement();

  // @todo Meta options (embedable met form)

  $form['actions'] = array(
    '#type'  => 'actions',
    'submit' => array(
      '#type'  => 'submit',
      '#value' => t("Create"),
    ),
    'cancel' => array(
      '#markup' => l(t("Cancel"), isset($_GET['destination']) ? $_GET['destination'] : 'admin/config/services/meta/misc'),
    ),
  );

  return $form;
}

/**
 * Add new meta entity form submit.
 */
function meta_admin_misc_add_submit($form, &$form_state) {

  $bundle = $form['#bundle'];

  $id = db_insert('meta_misc')
    ->fields(array(
      'bundle' => $bundle->getType(),
      'target' => (string)$form_state['values']['value'],
    ))
    ->execute();

  drupal_set_message(t("Custom meta information has been added"));

  $form_state['redirect'] = 'admin/config/services/meta/misc';
}

/**
 * Edit meta entity form.
 */
function meta_admin_misc_edit($form, &$form_state, MetaEntity $entity) {

  $form['#entity'] = $entity;

  $form['value'] = $entity->getBundleInstance()->getTargetFormElement();

  // @todo Meta options (embedable met form)

  $form['actions'] = array(
    '#type'  => 'actions',
    'submit' => array(
      '#type'  => 'submit',
      '#value' => t("Create"),
    ),
    'cancel' => array(
      '#markup' => l(t("Cancel"), isset($_GET['destination']) ? $_GET['destination'] : 'admin/config/services/meta/misc'),
    ),
  );

  // @todo Submit
  return $form;
}

/**
 * Delete meta entity form.
 */
function meta_admin_misc_delete($form, &$form_state, MetaEntity $entity) {

  $form['#entity'] = $entity;

  return confirm_form(
    $form,
    t("Do you want to delete this meta information?"),
    'admin/config/services/meta/misc/list'
  );
}

/**
 * Delete meta entity form.
 */
function meta_admin_misc_delete_submit($form, &$form_state) {

  db_delete('meta_misc')
    ->condition('id', $form['#entity']->getId())
    ->execute();

  drupal_set_message(t("Custom meta information has been deleted"));
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
