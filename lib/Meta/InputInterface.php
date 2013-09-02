<?php

namespace Meta;

interface InputInterface extends ComponentInterface
{
    /**
     * Get single value
     *
     * @param string $property
     *   Property name
     * @param string $type
     *   Entity type
     * @param object $entity
     *   Entity object
     *
     * @return mixed|null
     *   Null if nothing found
     */
    public function get($property, $type, $entity, $index = 0);

    /**
     * Get all values
     *
     * If object is multivalued return all the values, if not return an
     * array with only one value into it
     *
     * @param string $property
     *   Property name
     * @param string $type
     *   Entity type
     * @param object $entity
     *   Entity object
     *
     * @return mixed[]
     */
    public function getAll($property, $type, $entity);

    /**
     * Find properties matching this type on the given entity
     *
     * @param string $type
     *   Entity type
     * @param string $bundle
     *   Entity bundle
     *
     * @return array
     *   List of supported property names
     */
    public function findProperties($type, $bundle);
}
