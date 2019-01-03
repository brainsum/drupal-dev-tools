<?php

namespace Brainsum\DrupalDevTools\TwigLinter;

/**
 * Token parser for any block.
 *
 * @author Alexander <iam.asm89@gmail.com>
 */
class CatchAll extends \Twig_TokenParser {

  private $name;

  /**
   * {@inheritdoc}
   */
  public function __construct($name) {
    $this->name = $name;
  }

  /**
   * {@inheritdoc}
   */
  public function decideEnd(\Twig_Token $token): bool {
    return $token->test('end' . $this->name);
  }

  /**
   * {@inheritdoc}
   */
  public function parse(\Twig_Token $token) {
    $stream = $this->parser->getStream();

    while ($stream->getCurrent()->getType() !== \Twig_Token::BLOCK_END_TYPE) {
      $stream->next();
    }

    $stream->expect(\Twig_Token::BLOCK_END_TYPE);

    if ($this->hasBody($stream)) {
      $this->parser->subparse([$this, 'decideEnd'], TRUE);
      $stream->expect(\Twig_Token::BLOCK_END_TYPE);
    }

    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function getTag(): string {
    return $this->name;
  }

  /**
   * {@inheritdoc}
   */
  private function hasBody(\Twig_TokenStream $stream): bool {
    $look = 0;
    while ($token = $stream->look($look)) {
      if ($token->getType() === \Twig_Token::EOF_TYPE) {
        return FALSE;
      }

      if (
        $token->getType() === \Twig_Token::NAME_TYPE
        && $token->getValue() === 'end' . $this->name
      ) {
        return TRUE;
      }

      $look++;
    }

    return FALSE;
  }

}
