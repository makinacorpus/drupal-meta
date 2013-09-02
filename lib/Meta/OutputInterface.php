<?php

namespace Meta;

/**
 * Defines a property to node converter
 */
interface OutputInterface
{
    /**
     * Get default options
     *
     * @return mixed[]
     */
    public function getDefaultOptions();

    /**
     * Get node from the given data
     *
     * @param string $type
     *   Input type
     * @param mixed[] $values
     *   Values to export
     * @param array $options
     *   Runtime options if any
     *
     * @return Node[]
     *   Built nodes, false in case of error (not applicable input type or
     *   entity type), null if no data found
     */
    public function buildNode($type, array $values, array $options = array());
}
