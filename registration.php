<?php
require_once('tools/sdk/internals/autoload.php');

/**
 * Register Conotoxia_Pay Component
 */

use Magento\Framework\Component\ComponentRegistrar;

ComponentRegistrar::register(ComponentRegistrar::MODULE, 'Conotoxia_Pay', __DIR__);