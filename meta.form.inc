<?php

/**
 * @file
 * Generic mapping form is a business for itself, hence needs a file for
 * code readabilty and maintainance.
 */

/**
 * Generic mapping configuration form.
 */
function meta_mapping_form($form, &$form_state, \Meta\PluginInterface $plugin, $bundleid) {

  $form['#tree']   = true;
  $form['#theme']  = 'meta_mapping_form';
  $form['#id']     = 'meta-mapping-form';

  list($type, $bundle) = explode(':', $bundleid);

  $form['#plugin'] = $plugin;
  $form['#etype']  = $type;
  $form['#bundle'] = $bundle;

  $service = meta_service_get();

  $properties = array();
  $properties_options = array();
  foreach ($plugin->getPropertyList() as $property => $info) {
    if (is_string($info)) {
      $info = array('label' => $info);
    }
    $properties[$property] = $info;
    $properties_options[$property] = $info['label'];
  }

  // We may be in AJAX refresh, so add any element that could have been
  // added by the user.
  if (!empty($form_state['storage']['mapping'])) {
    $mapping = $form_state['storage']['mapping'];
  } else {
    $mapping = $plugin->getMapping($type, $bundle);
  }

  $mandatoryProperties = $plugin->getMandatoryProperties();
  foreach ($mandatoryProperties as $name) {
    if (!isset($mapping[$name])) {
      $mapping = array_merge(array($name => null), $mapping);
    }
  }

  $count = 1;
  foreach ($mapping as $target) {

    $name = $target['name'];

    if (!isset($properties[$name])) {
      continue; // Invalid property
    }

    $info = $properties[$name];

    if (empty($info['output'])) {
      continue; // Cannot find anything here.
    }
    $inputs = $service->getInputFor($info['output']);

    if (empty($inputs)) {
      // @todo Set mapping name and property into value fields
      // so the conf is not lost at save time.
      continue; // Cannot satisfy this input.
    }

    $options = array(null => '-- ' . t("Select (remove)") . ' --');
    foreach ($inputs as $inputType => $input) {
      foreach ($input->findProperties($type, $bundle) as $property => $label) {
        $options[$inputType . ':' . $property] = $label;
      }
    }

    if (empty($options)) {
      // @todo Set mapping name and property into value fields
      // so the conf is not lost at save time.
      continue; // Cannot satisfy this input.
    }

    $element = array();

    $element['name'] = array(
      '#title'         => t("Property"),
      '#type'          => 'select',
      '#options'       => array($name => $info['label']),
      '#default_value' => $name,
      '#id'            => 'prop-' . $count,
    );

    $element['custom'] = array(
      '#title'         => t("Custom name"),
      '#type'          => 'textfield',
      '#size'          => 20,
      '#default_value' => $name,
    );

    if ($plugin->allowsArbitrary()) {
      if (!empty($target['custom'])) {
        $element['custom']['#default_value'] = $target['custom'];
      } else {
        $element['custom']['#default_value'] = $name;
      }
    } else {
      $element['custom']['#default_value'] = $name;
      $element['custom']['#readonly']      = true;
      $element['custom']['#disabled']      = true;
    }

    $element['input'] = array(
      '#title'         => t("Data target"),
      '#type'          => 'select',
      '#options'       => $options,
      '#id'            => 'prop-method-' . $count,
    );
    if (isset($target['input'])) {
      $element['input']['#default_value'] = $target['input'];
    }

    $element['weight'] = array(
      '#title'         => t("Order"),
      '#type'          => 'weight',
      '#title'         => t("Weight"),
      '#default_value' => $count++,
    );

    $form['properties'][] = $element;
  }

  $form['add']['property'] = array(
    '#title'         => t("Property"),
    '#type'          => 'select',
    '#options'       => $properties_options,
    '#default_value' => null,
    '#id'            => 'prop-' . $count,
    '#readonly'      => true,
  );
  $form['add']['submit'] = array(
    '#value' => t("Add"),
    '#type'  => 'submit',
    '#ajax'  => array(
      'wrapper'  => 'meta-mapping-form',
      'method'   => 'replace',
      'callback' => 'meta_mapping_form_ajax_add',
    ),
    '#submit' => array('meta_mapping_form_submit_add'),
  );

  $form['actions'] = array(
    '#type'  => 'actions',
    'submit' => array(
      '#type'   => 'submit',
      '#value'  => t("Save"),
      '#submit' => array('meta_mapping_form_submit_save'),
    ),
    'cancel' => array(
      '#markup' => l(t("Cancel"), isset($_GET['destination']) ? $_GET['destination'] : 'admin/config/services/meta/bundles')
    ),
  );

  return $form;
}

/**
 * Single bundle customization add new element form submit.
 */
function meta_mapping_form_submit_add($form, &$form_state) {

  // Find out values and rebuild mapping from it.
  $mapping = array();
  foreach ($form_state['values']['properties'] as $data) {
    $mapping[] = array(
      'name'   => $data['name'],
      'input'  => $data['input'],
      'custom' => $data['custom'],
    );
  }

  $mapping[] = array(
    'name'  => $form_state['values']['add']['property'],
    'input' => null,
  );

  // So that the form handler will find the temporary values its working
  // on and display everything correctly.
  $form_state['storage']['mapping'] = $mapping;
  $form_state['rebuild'] = true;
}

/**
 * Single bundle customization add new element ajax handler.
 */
function meta_mapping_form_ajax_add($form, &$form_state) {
  return $form;
}

/**
 * Single bundle customization form submit.
 */
function meta_mapping_form_submit_save($form, &$form_state) {

  $plugin = $form['#plugin'];
  $type   = $form['#etype'];
  $bundle = $form['#bundle'];

  // Find out values and rebuild mapping from it.
  $mapping = array();
  foreach ($form_state['values']['properties'] as $data) {
    if (!empty($data['input'])) { // Remove when input is not selected.
      $entry = array(
        'name'   => $data['name'],
        'input'  => $data['input'],
      );
      if ($plugin->allowsArbitrary() && $data['custom'] !== $data['name']) {
        $entry['custom'] = $data['custom'];
      }
      $mapping[] = $entry;
    }
  }

  $plugin->setMapping($mapping, $type, $bundle);
  drupal_set_message(t("Changes have been saved."));

  $form_state['redirect'] = 'admin/config/services/ognode/bundles';
}

/**
 * Single bundle customization theme.
 */
function theme_meta_mapping_form(&$variables) {

  $form   = &$variables['form'];
  $output = '';

  $rows   = array();
  $header = array(
    t("Property"),
    t("Name"),
    t("Data target"),
    t("Order"),
  );

  if (!empty($form['properties'])) {
    foreach (element_children($form['properties']) as $index) {

      // Remove element titles since we're adding them into the table heaer.
      unset(
        $form['properties'][$index]['name']['#title'],
        $form['properties'][$index]['custom']['#title'],
        $form['properties'][$index]['input']['#title'],
        $form['properties'][$index]['weight']['#title']
      );

      $form['properties'][$index]['weight']['#attributes']['class'][] = 'og-meta-order';

      $row = array(
        'data' => array(),
        'class' => array('draggable'),
      );
      $row['data'][] = drupal_render($form['properties'][$index]['name']);
      $row['data'][] = drupal_render($form['properties'][$index]['custom']);
      $row['data'][] = drupal_render($form['properties'][$index]['input']);
      $row['data'][] = drupal_render($form['properties'][$index]['weight']);
      $rows[] = $row;
    }

    // Main table.
    drupal_add_tabledrag('og-meta-order', 'order', 'sibling', 'og-meta-order');
    $output = theme('table', array('header' => $header, 'rows' => $rows, 'attributes' => array('id' => 'og-meta-order')));
  }

  // Add table.
  unset($form['add']['property']['#title']);
  $output .= theme('table', array(
    'header' => array(t("Add a new element"), ''),
    'rows' => array(
      array(
        drupal_render($form['add']['property']),
        drupal_render($form['add']['submit']),
      ),
    )
  ));

  return $output . drupal_render_children($form);
}
