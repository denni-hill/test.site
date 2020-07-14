<?php

/**
 * Interface iApi
 * All the controllers which should handle the api requests should implement this interface. IMPORTANT: method ApiIndex() will be called if api request received.
 */
interface iApi
{
    public function ApiIndex(array $appInfo = array());
}