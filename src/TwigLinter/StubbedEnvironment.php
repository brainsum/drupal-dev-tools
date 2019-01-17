<?php

namespace Brainsum\DrupalDevTools\TwigLinter;

/**
 * Stub environment.
 *
 * Provides stubs for all filters, functions, tests and tags that
 * are not defined in twig's core.
 */
class StubbedEnvironment extends \Twig_Environment {

  /**
   * Stubbed filters.
   *
   * @var \Twig_SimpleFilter[]
   */
  private $stubFilters;

  /**
   * Stubbed functions.
   *
   * @var \Twig_SimpleFunction[]
   */
  private $stubFunctions;

  /**
   * Stubbed tests.
   *
   * @var \Twig_SimpleTest[]
   */
  private $stubTests;

  /**
   * Token parser broker.
   *
   * @var \Twig_TokenParserBroker
   */
  protected $parsers;

  /**
   * {@inheritdoc}
   */
  public function __construct(\Twig_LoaderInterface $loader = NULL, $options = []) {
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
      $this->stubFilters[$name] = new \Twig_SimpleFilter('stub', function () {});
    }

    return $this->stubFilters[$name];
  }

  /**
   * {@inheritdoc}
   */
  public function getFunction($name) {
    if (!isset($this->stubFunctions[$name])) {
      $this->stubFunctions[$name] = new \Twig_SimpleFunction('stub', function () {});
    }

    return $this->stubFunctions[$name];
  }

  /**
   * {@inheritdoc}
   */
  public function getTest($name) {
    if (!isset($this->stubTests[$name])) {
      $this->stubTests[$name] = new \Twig_SimpleTest('stub', function () {});
    }

    return $this->stubTests[$name];
  }

}
