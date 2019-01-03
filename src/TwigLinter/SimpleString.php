<?php

namespace Brainsum\DrupalDevTools\TwigLinter;

/**
 * Simple string wrapper.
 *
 * @package Brainsum\DrupalDevTools\TwigLinter
 */
class SimpleString {

  /**
   * The string.
   *
   * @var string
   */
  protected $string;

  /**
   * SimpleString constructor.
   *
   * @param string $string
   *   The string.
   */
  public function __construct(string $string) {
    $this->string = $string;
  }

  /**
   * {@inheritdoc}
   */
  public function __toString() {
    return $this->string;
  }

}
