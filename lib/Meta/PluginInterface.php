<?php

namespace Meta;

interface PluginInterface extends ComponentInterface
{
    /**
     * Does this plugin allow arbitrary user input
     *
     * @return boolean
     *   True if the object allows user input
     */
    public function allowsArbitrary();

    /**
     * Is this plugin configured for allowing user modifications for the given
     * bundle
     *
     * @return boolean
     *   True if the end user can edit node settings when editing a node
     */
    public function allowsUserModifications($type, $bundle = null);

    /**
     * Enable or disable
     *
     * @param boolean $toggle
     *   False or true respectively disable or enable
     * @param string $type
     *   Entity type
     * @param string $bundle
     *   Bundle
     */
    public function toggleUserModifications($type, $bundle = null, $toggle = true);

    /**
     * Get available properties to expose
     *
     * @return array[]
     *   Key value pairs where keys are properties names and values are
     *   a machine description of the property, containing keys:
     *    - 'label' : human readable label
     *    - 'accepts' : string[] types it accepts as input
     */
    public function getPropertyList();

    /**
     * Get mandatory properties to expose (order matters)
     *
     * @return string[]
     *   Ordererd property name list
     */
    public function getMandatoryProperties();

    /**
     * Get default mapping
     *
     * @return array[]
     *
     * @see examples/mapping.php
     *   For a complex and complete mapping example
     */
    public function getDefaultMapping();

    /**
     * Get field name for this plugin
     *
     * @return string
     */
    public function getFieldName();

    /**
     * Set mapping for field or instance
     *
     * @param array $mapping
     *   New mapping
     * @param string $type
     *   Entity type
     * @param string $bundle
     *   Entity bundle
     */
    public function setMapping(array $mapping, $type, $bundle = null);

    /**
     * Get applicable mapping for given context
     *
     * @param string $type
     *   Entity type.
     * @param string $bundle
     *   Entity bundle.
     *
     * @return array[]
     */
    public function getMapping($type, $bundle = null);
}
