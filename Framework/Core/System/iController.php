<?php

/**
 * Interface iController
 * All the controllers which should handle non-api requests should implement this interface. IMPORTANT: method Index() will be called if non-api request received.
 */
interface iController
{
    public function Index(array $appInfo);
}