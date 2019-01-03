<?php

namespace Brainsum\DrupalDevTools\TwigLinter;

use Twig_TokenParserBroker;

/**
 * Broker providing stubs for all tags that are not defined.
 *
 * @author Alexander <iam.asm89@gmail.com>
 *
 * @deprecated
 *
 * @todo: Replace Twig_TokenParserBroker.
 */
class StubbedTokenParserBroker extends Twig_TokenParserBroker {

  protected $parser;

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
   */
  public function getParser() {
    return $this->parser;
  }

  /**
   * {@inheritdoc}
   *
   * \Twig_Parser
   */
  public function setParser(\Twig_ParserInterface $parser) {
    $this->parser = $parser;
  }

}
