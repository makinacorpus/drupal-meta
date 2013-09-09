<?php

namespace Meta\Entity;

use Meta\ComponentInterface;

/**
 * This interface provides basic implementation of Meta entity bundles.
 *
 * Bundle provide a target identifier that may be a string or an integer
 * identifier, which will be saved as the only entity data
 */
interface BundleInterface extends ComponentInterface
{
    /**
     * Get human readable description
     *
     * @return string
     *   Localized description
     */
    public function getDescription();

    /**
     * Derivates from the current context the target form element
     *
     * Any validate handler must be set directly as element validate handler
     *
     * @param string $value
     *   Default value from database in case of edit or prepopulated form
     * @param boolean $readonly
     *   Set to true in case of a prepopulated form (for example, add the
     *   current page meta entity): when this is set, the element must be
     *   readonly (the real value will be set in the form as a #value'd
     *   element instead, so this generated element will only be pure visua
     *   candy
     *
     * @return array
     */
    public function getTargetFormElement($value = null, $readonly = false);
}
