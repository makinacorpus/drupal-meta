<?php

namespace Meta;

interface ServiceAwareInterface
{
    /**
     * Get service
     *
     * @return Service
     */
    public function getService();
}
