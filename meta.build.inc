<?php

/**
 * @file
 * Build specific functions.
 */

use Meta\Node;
use Meta\PluginInterface;

/**
 * Build meta for given entity
 *
 * @param PluginInterface $plugin
 *   Plugin.
 * @param string $type
 *   Entity type.
 * @param object $entity
 *   Entity.
 *
 * @return Node[]
 *   Tree of meta nodes.
 */
function _meta_entity_process(PluginInterface $plugin, $type, $entity) {

  list(,, $bundle) = entity_extract_ids($type, $entity);

  $service = $plugin->getService();
  $nodes   = array();

  if ($mapping = $plugin->getMapping($type, $bundle)) {

    // @todo This code should be moved out.
    $properties = $plugin->getPropertyList();

    foreach ($mapping as $item) {

      if (!isset($properties[$item['name']])) {
        continue; // Unsupported item, drop it.
      }

      if (isset($properties[$item['name']]['output'])) {
        $outputKeys = $properties[$item['name']]['output'];
      } else {
        $outputKeys = array('scalar');
      }

      list($inputKey, $name) = explode(':', $item['input']);
      if (!$input = $service->getInput($inputKey)) {
        continue; // Could not find input instance, drop it.
      }
      if (!$datatype = $service->getInputDatatype($inputKey)) {
        continue; // No datatype provided.
      }

      if (!$output = $service->getOutputFor($datatype, $outputKeys)) {
        continue; // Could not find output instance, drop it.
      }

      $newNodes = $output->buildNode(
        $datatype,
        $input->getAll($name, $type, $entity),
        $output->getDefaultOptions()
      );

      // @todo Options.
      foreach ($newNodes as $node) {
        $node->setName($item['name']);
        $nodes[] = $node;
      }
    }
  }

  return $nodes;
}
