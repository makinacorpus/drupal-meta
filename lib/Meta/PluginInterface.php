<?php

namespace Meta;

interface PluginInterface extends ServiceAwareInterface
{
    /**
     * Get label
     *
     * @return string
     */
    public function getLabel();

    /**
     * Set label
     *
     * @param string $label
     */
    public function setLabel($label);

    /**
     * Get type
     *
     * @return string
     */
    public function getType();

    /**
     * Set type
     *
     * @param string $type
     */
    public function setType($type);

    /**
     * Does this plugin allow arbitrary user input
     *
     * @return boolean
     *   True if the object allows user input
     */
    public function allowsArbitrary();

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
