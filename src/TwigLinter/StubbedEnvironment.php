<?php

namespace Brainsum\DrupalDevTools\TwigLinter;

use Twig_LoaderInterface;

/**
 * Environment providing stubs for all filters, functions, tests and tags that
 * are not defined in twig's core.
 *
 * @author Alexander <iam.asm89@gmail.com>
 */
class StubbedEnvironment extends \Twig_Environment {

  private $stubFilters;

  private $stubFunctions;

  private $stubTests;

  protected $parsers;

  /**
   * {@inheritdoc}
   */
  public function __construct(Twig_LoaderInterface $loader = NULL, $options = []) {
    parent::__construct($loader, $options);

    $this->addExtension(new StubbedCore());
    $this->initExtensions();

    $broker = new StubbedTokenParserBroker();
    $this->parsers->addTokenParserBroker($broker);
  }

  /**
   * {@inheritdoc}
   */
  public function getFilter($name) {
    if (!isset($this->stubFilters[$name])) {
      $this->stubFilters[$name] = new \Twig_SimpleFilter('stub', 'stub');
    }

    return $this->stubFilters[$name];
  }

  /**
   * {@inheritdoc}
   */
  public function getFunction($name) {
    if (!isset($this->stubFunctions[$name])) {
      $this->stubFunctions[$name] = new \Twig_SimpleFunction('stub', 'stub');
    }

    return $this->stubFunctions[$name];
  }

  /**
   * {@inheritdoc}
   */
  public function getTest($name) {
    if (!isset($this->stubTests[$name])) {
      $this->stubTests[$name] = new \Twig_SimpleTest('stub', function () {
      });
    }

    return $this->stubTests[$name];
  }

}
