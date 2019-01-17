<?php

namespace Brainsum\DrupalDevTools\TwigLinter;

/**
 * Overridden core extension to stub tests.
 */
class StubbedCore extends \Twig_Extension_Core {

  /**
   * {@inheritdoc}
   */
  protected function getTestNodeClass(\Twig_Parser $parser, $name): string {
    return 'Twig_Node_Expression_Test';
  }

  /**
   * {@inheritdoc}
   */
  protected function getTestName(\Twig_Parser $parser, $line) {
    try {
      return parent::getTestName($parser, $line);
    }
    catch (\Twig_Error_Syntax $exception) {
      return 'null';
    }
  }

}
