<?php

declare(strict_types=1);

require __DIR__.'/definitions.php';
require __DIR__.'/functions.php';
require __DIR__.'/../src/Autoload.php';

\spl_autoload_register(
    \CKPL\Pay\Autoload::class.'::resolve'
);
