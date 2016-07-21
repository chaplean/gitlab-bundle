<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Composer\Autoload\ClassLoader;

/**
 * @var ClassLoader $loader
 */
$loader = require_once __DIR__  . '/../vendor/autoload.php';

require_once __DIR__ . '/AppKernel.php';

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

return $loader;
