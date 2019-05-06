<?php

namespace Brainsum\DrupalDevTools\TwigLinter;

use Twig\Node\Node;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;
use Twig\TokenStream;

/**
 * Token parser for any block.
 */
class CatchAll extends AbstractTokenParser {

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
  public function decideEnd(Token $token): bool {
    return $token->test('end' . $this->name);
  }

  /**
   * {@inheritdoc}
   */
  public function parse(Token $token): ?Node {
    $stream = $this->parser->getStream();

    while ($stream->getCurrent()->getType() !== Token::BLOCK_END_TYPE) {
      $stream->next();
    }

    $stream->expect(Token::BLOCK_END_TYPE);

    if ($this->hasBody($stream)) {
      $this->parser->subparse([$this, 'decideEnd'], TRUE);
      $stream->expect(Token::BLOCK_END_TYPE);
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
  private function hasBody(TokenStream $stream): bool {
    $look = 0;
    // @todo: Handle exception?
    while ($token = $stream->look($look)) {
      if ($token->getType() === Token::EOF_TYPE) {
        return FALSE;
      }

      if (
        $token->getType() === Token::NAME_TYPE
        && $token->getValue() === 'end' . $this->name
      ) {
        return TRUE;
      }

      $look++;
    }

    return FALSE;
  }

}
