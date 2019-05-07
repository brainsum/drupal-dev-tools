<?php

namespace Brainsum\DrupalDevTools\TwigLinter;

use Twig\Environment;
use Twig\Loader\LoaderInterface;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Twig\TwigTest;

/**
 * Stub environment.
 *
 * Provides stubs for all filters, functions, tests and tags that
 * are not defined in twig's core.
 */
class StubbedEnvironment extends Environment {

  /**
   * Token parser broker.
   *
   * @var \Twig_TokenParserBroker
   */
  protected $parsers;

  /**
   * Stubbed filters.
   *
   * @var \Twig\TwigFilter[]
   */
  private $stubFilters;

  /**
   * Stubbed functions.
   *
   * @var \Twig\TwigFunction[]
   */
  private $stubFunctions;

  /**
   * Stubbed tests.
   *
   * @var \Twig\TwigTest[]
   */
  private $stubTests;

  /**
   * Stubbed callable.
   *
   * @var \Closure
   */
  private $stubCallable;

  /**
   * {@inheritdoc}
   */
  public function __construct(LoaderInterface $loader = NULL, $options = []) {

    parent::__construct($loader, $options);
    $this->stubCallable = function () {
      /* This will be used as stub filter, function or test */
    };
    $this->stubFilters = [];
    $this->stubFunctions = [];
    if (isset($options['stub_tags'])) {
      foreach ($options['stub_tags'] as $tag) {
        $this->addTokenParser(new CatchAll($tag));
      }
    }
    $this->stubTests = [];
    if (isset($options['stub_tests'])) {
      foreach ($options['stub_tests'] as $test) {
        $this->stubTests[$test] = new TwigTest('stub', $this->stubCallable);
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getFilter($name) {
    if (!isset($this->stubFilters[$name])) {
      $this->stubFilters[$name] = new TwigFilter('stub', $this->stubCallable);
    }

    return $this->stubFilters[$name];
  }

  /**
   * {@inheritdoc}
   */
  public function getFunction($name) {
    if (!isset($this->stubFunctions[$name])) {
      $this->stubFunctions[$name] = new TwigFunction('stub', $this->stubCallable);
    }

    return $this->stubFunctions[$name];
  }

  /**
   * {@inheritdoc}
   */
  public function getTest($name) {
    $test = parent::getTest($name);

    if ($test) {
      return $test;
    }

    return $this->stubTests[$name] ?? FALSE;
  }

}
