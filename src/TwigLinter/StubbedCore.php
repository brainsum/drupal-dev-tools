<?php

namespace Brainsum\DrupalDevTools\TwigLinter;

use Twig\Extension\CoreExtension;

/**
 * Overridden core extension to stub tests.
 */
class StubbedCore extends CoreExtension {

  /**
   * {@inheritdoc}
   */
  protected function getTestNodeClass(): string {
    return 'Twig_Node_Expression_Test';
  }

  /**
   * {@inheritdoc}
   */
  protected function getTestName(): ?string {
    return 'null';
  }

}
