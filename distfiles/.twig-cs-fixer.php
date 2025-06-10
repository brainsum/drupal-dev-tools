<?php

use Brainsum\TwigCsFixer\Ruleset\DrupalTwigCsFixer;

// Create a new configuration
$config = new TwigCsFixer\Config\Config();

// Use our custom DrupalTwigCsFixer ruleset
$drupalTwigCsFixer = new DrupalTwigCsFixer();
$config->setRuleset($drupalTwigCsFixer->getRuleset());

// Allow non-fixable rules
$config->allowNonFixableRules();

// Configure finder to scan all relevant template directories
$finder = new TwigCsFixer\File\Finder();
// Add your theme and custom module templates directories
$finder->in([
  __DIR__ . '/web/themes/custom',
  __DIR__ . '/web/modules/custom',
]);
$finder->exclude('vendor');
$config->setFinder($finder);

return $config;
