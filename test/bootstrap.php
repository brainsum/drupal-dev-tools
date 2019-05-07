<?php

/**
 * @file
 * Test bootstrap file.
 *
 * Copied from asm89/twig-lint.
 * @see https://github.com/asm89/twig-lint
 */

error_reporting(E_ALL | E_STRICT);

if (file_exists($file = __DIR__ . '/../vendor/autoload.php')) {
  $autoload = require_once $file;
}
else {
  throw new RuntimeException('Install dependencies to run test suite.');
}
