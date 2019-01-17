<?php

namespace Brainsum\DrupalDevTools\TwigLinter;

/**
 * Broker providing stubs for all tags that are not defined.
 *
 * @deprecated
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
