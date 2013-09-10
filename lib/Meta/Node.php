<?php

namespace Meta;

class Node
{
    /**
     * Default value attribute name
     */
    const ATTR_VALUE = 'content';

    /**
     * Default name property name
     */
    const ATTR_NAME = 'name';

    /**
     * @var string
     */
    protected $prefix;

    /**
     * @var string
     */
    protected $nameAttr = Node::ATTR_NAME;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var node
     */
    protected $parent;

    /**
     * @var Node[]
     */
    protected $properties = array();

    /**
     * @var string
     */
    protected $valueAttr = Node::ATTR_VALUE;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * Default constructor
     *
     * @param string $name
     *   Name
     * @param string $value
     *   Value
     * @param string $prefix
     *   Prefix if any
     * @param Node $parent
     *   Parent if any
     * @param string $nameAttr
     *   Name attribute name if different from default
     * @param string $valueAttr
     *   Value attribute name if different from default
     */
    public function __construct(
        $value       = null,
        $name        = null,
        $prefix      = null,
        Node $parent = null,
        $nameAttr    = null,
        $valueAttr   = null)
    {
        $this->value  = $value;
        $this->name   = $name;
        $this->prefix = $prefix;
        $this->parent = $parent;

        if (null !== $nameAttr) {
            $this->nameAttr = $nameAttr;
        }
        if (null !== $valueAttr) {
            $this->valueAttr = $valueAttr;
        }
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Set prefix
     *
     * @param string $prefix
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }

    /**
     * Set parent
     *
     * @param Node $parent
     */
    public function setParent(Node $parent)
    {
        $this->parent = $parent;
    }

    /**
     * Add property
     *
     * @param Node $node
     *   Property
     */
    public function addProperty(Node $node)
    {
        $node->setParent($this);
        $this->properties[] = $node;
    }

    /**
     * Add properties
     *
     * @param Node[] $nodes
     *   Properties
     */
    public function addProperties(array $nodes)
    {
        foreach ($nodes as $node) {
            $this->addProperty($node);
        }
    }

    /**
     * Escape string for HTML usage
     *
     * @param string $text
     *
     * @return string
     */
    public function escapeHtmlString($text)
    {
        return htmlspecialchars((string)$text, ENT_COMPAT /* | ENT_HTML5 */, 'UTF-8');
    }

    /**
     * Drupal specific: render as build array
     *
     * @return array
     */
    public function build($prefix = null)
    {
        $build = array();

        if (null !== $prefix) {
            $name = $prefix . ":" . $this->name;
        } else if (null !== $this->prefix) {
            $name = $this->prefix . ":" . $this->name;
        } else {
            $name = $this->name;
        }

        if (null !== $this->value) { // Do not display empty nodes
            $data = array(
                '#tag'        => 'meta',
                '#attributes' => array(
                    $this->nameAttr  => $this->escapeHtmlString($name),
                    $this->valueAttr => (string)$this->escapeHtmlString($this->value),
                )
            );
        }

        $build[] = $data;

        foreach ($this->properties as $node) {
            $build = array_merge($build, $node->build($name));
        }

        return $build;
    }

    /**
     * Convert to string
     *
     * Override to do anything else than HTML
     *
     * @param string $prefix
     *   Prefix override for this instance and all children
     *
     * @return string
     */
    public function render($prefix = null)
    {
        $output = '';

        if (null !== $prefix) {
            $name = $prefix . ":" . $this->name;
        } else if (null !== $this->prefix) {
            $name = $this->prefix . ":" . $this->name;
        } else {
            $name = $this->name;
        }

        if (null !== $this->value) { // Do not display empty nodes
            $output .= '<meta ' . $this->nameAttr . '="' . $name . '" ' . $this->valueAttr . '="' . ((string)$this->value) . '">' . "\n";
        }

        if (count($this->properties)) {
            foreach ($this->properties as $node) {
                $output .= $node->render($name);
            }
        }

        return $output;
    }

    /**
     * Convert to string
     *
     * Override to do anything else than HTML
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render($this->prefix);
    }
}
