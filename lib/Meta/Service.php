<?php

namespace Meta;

class Service
{
    /**
     * @var array
     */
    protected $inputInfo;

    /**
     * @var array
     */
    protected $pluginInfo;

    /**
     * @var array
     */
    protected $outputInfo;

    /**
     * Ensure plugin definition
     *
     * @param array $info
     *
     * @return boolean
     */
    protected function checkInfo($info)
    {
        if (!isset($info['class']) || !class_exists($info['class'])) {
            return false;
        }
        return true;
    }

    /**
     * Invoke hook and build info array from
     *
     * @param string $hook
     *
     * @return array
     */
    protected function buildInfo($hook)
    {
        $ret = array();

        foreach (module_invoke_all($hook) as $key => $info) {
            if ($this->checkInfo($info)) {
                $ret[$key] = $info;
            }
        }

        drupal_alter($hook, $ret);

        return $ret;
    }

    /**
     * Create plugin instance from info
     *
     * @param array $info
     * @param string $key
     *
     * @return mixed
     */
    protected function createInstance(array $info, $key = null)
    {
        if (isset($info['class']) && class_exists($info['class'])) {
            $instance = new $info['class']();
            if ($instance instanceof PluginInterface) {
                if (null !== $key) {
                    $instance->setType($key);
                }
                if (isset($info['label'])) {
                    $instance->setLabel($info['label']);
                }
            }
            if ($instance instanceof ServiceAwareInterface) {
                $instance->setService($this);
            }
            return $instance;
        }
    }

    /**
     * Get plugin info
     *
     * @return array[]
     */
    public function getPluginInfo()
    {
        if (null === $this->pluginInfo) {
            $this->pluginInfo = $this->buildInfo('meta_info');
        }

        return $this->pluginInfo;
    }

    /**
     * Get all plugin instances
     *
     * @return PluginInterface[]
     */
    public function getPluginAll()
    {
        $ret = array();

        foreach ($this->getPluginInfo() as $key => $info) {
            $ret[$key] = $this->createInstance($info, $key);
        }

        return $ret;
    }

    /**
     * Get input instances for types
     *
     * @param string $key
     *   Plugin key
     *
     * @return PluginInterface
     */
    public function getPlugin($key)
    {
        $info = $this->getPluginInfo();

        if (isset($info[$key])) {
            return $this->createInstance($info[$key], $key);
        }
    }

    /**
     * Get input into
     *
     * @return array[]
     */
    public function getInputInfo()
    {
        if (null === $this->inputInfo) {
            $this->inputInfo = $this->buildInfo('meta_info_input');
        }

        return $this->inputInfo;
    }

    /**
     * Get output instance
     *
     * @param string $key
     *
     * @return InputInterface
     */
    public function getInput($key)
    {
        $info = $this->getInputInfo();

        if (isset($info[$key])) {
            return $this->createInstance($info[$key], $key);
        }
    }

    /**
     * Get input instances for the given output types
     *
     * This method is meant for admin UI.
     *
     * @param string[] $outputTypes
     *
     * @return InputInterface[]
     */
    public function getInputFor(array $outputTypes)
    {
        $ret = array();

        $outputInfo = $this->getOutputInfo();
        $datatypes  = array();

        foreach ($outputTypes as $key) {
            if (isset($outputInfo[$key]['input'])) {
                $datatypes = array_merge($datatypes, $outputInfo[$key]['input']);
            }
        }

        if (empty($datatypes)) {
            return $ret;
        }

        foreach ($this->getInputInfo() as $key => $info) {
            if (isset($info['output'])) {
                if (in_array($info['output'], $datatypes)) {
                    $ret[$key] = $this->getInput($key);
                }
            }
        }

        return $ret;
    }

    /**
     * Get datatype for input type
     *
     * @param string $key
     *
     * @return string
     */
    public function getInputDatatype($key)
    {
        $info = $this->getInputInfo();

        if (isset($info[$key]['output'])) {
            return $info[$key]['output'];
        }
    }

    /**
     * Get output into
     *
     * @return array[]
     */
    public function getOutputInfo()
    {
        if (null === $this->outputInfo) {
            $this->outputInfo = $this->buildInfo('meta_info_output');
        }

        return $this->outputInfo;
    }

    /**
     * Get output instance
     *
     * @param string $key
     *
     * @return OutputInterface
     */
    public function getOutput($key)
    {
        $info = $this->getOutputInfo();

        if (isset($info[$key])) {
            return $this->createInstance($info[$key], $key);
        }
    }

    /**
     * Get best suitable output for given datatypes
     *
     * @param string $inputKey
     * @param string[] $filter
     *
     * @return OutputInterface
     */
    public function getOutputFor($datatype, array $filter = null)
    {
        $candidates = array();
        $outputInfo = $this->getOutputInfo();

        if (null !== $filter) {
            $outputInfo = array_intersect_key(
                $outputInfo,
                array_flip($filter));
        }

        foreach ($outputInfo as $key => $info) {
            if (in_array($datatype, $info['input'])) {
                $candidates[] = $key;
            }
        }

        if (!empty($candidates)) {

            /*
            if (null !== $filter && count($filter)) {
                array_multisort($filter, SORT_ASC, SORT_STRING, $candidates);
            }
             */

            foreach ($candidates as $key) {
                if ($output = $this->getOutput($key)) {
                    return $output;
                }
            }
        }
    }

    /**
     * Get enabled plugins key for the given entity
     *
     * @param string $type
     * @param string $entity
     *
     * @return string[]
     */
    public function getPluginsFor($type, $entity)
    {
        $ret = array();

        foreach ($this->getPluginInfo() as $key => $info) {
            if (fieldture_entity_is_enabled('meta:' . $key, $type, $entity)) {
                $ret[$key] = $this->getPlugin($key);
            }
        }

        return $ret;
    }

    /**
     * Get global fieldture info
     *
     * @return array
     */
    public function getFieldtureInfo()
    {
        $ret = array();

        foreach ($this->getPluginAll() as $type => $plugin) {
            $ret['meta:' . $type] = array(
                'label' => $plugin->getLabel(),
                'field' => array(
                    'field_name'  => $plugin->getFieldName(),
                    'type'        => META_FIELD,
                    'cardinality' => 1,
                ),
                'custom_ui' => true,
                'instance' => array(
                    'label'   => $plugin->getLabel(),
                    'display' => array(
                        'default' => array(
                            'type' => 'hidden',
                        ),
                    ),
                    'settings' => array(
                        'mapping' => $plugin->getDefaultMapping(),
                    ),
                ),
            );
        }

        return $ret;
    }
}
