<?php

namespace Brainsum\DrupalDevTools\TwigLinter;

/**
 * Broker providing stubs for all tags that are not defined.
 *
 * phpcs:disable
 * @deprecated
 * phpcs:enable
 *
 * @note: drupal/coder newer versions (e.g. 8.3.3) add rules about the
 * deprecated annotation which we don't want to use.
 *
 * @todo: Replace Twig_TokenParserBroker.
 */
class StubbedTokenParserBroker extends \Twig_TokenParserBroker {

  /**
   * Parser.
   *
   * @var \Twig_Parser
   */
  protected $parser;

  /**
   * Parsers.
   *
   * @var \Twig_TokenParser[]
   */
  protected $parsers;

  /**
   * {@inheritdoc}
   */
  public function getTokenParser($name) {
    if (!isset($this->parsers[$name])) {
      $this->parsers[$name] = new CatchAll($name);
      $this->parsers[$name]->setParser($this->parser);
    }

    return $this->parsers[$name];
  }

  /**
   * {@inheritdoc}
   *
   * @todo: \Twig_Parser as deprecation fix.
   */
  public function setParser(\Twig_ParserInterface $parser) {
    $this->parser = $parser;
  }

}
