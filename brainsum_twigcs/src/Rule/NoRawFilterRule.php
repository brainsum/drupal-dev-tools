<?php

namespace Brainsum\Twigcs\Rule;

use FriendsOfTwig\Twigcs\Rule\AbstractRule;
use FriendsOfTwig\Twigcs\Rule\RuleInterface;
use FriendsOfTwig\Twigcs\TwigPort\Token;
use FriendsOfTwig\Twigcs\TwigPort\TokenStream;

class NoRawFilterRule extends AbstractRule implements RuleInterface
{
  public function check(TokenStream $tokens)
  {

    $violations = [];
    $ignoreRawLine = -1;

    while (!$tokens->isEOF()) {
      $token = $tokens->getCurrent();

      if (Token::COMMENT_TYPE === $token->getType() && '@TwigCsIgnoreRaw' === $token->getValue()) {
        $ignoreRawLine = $token->getLine();
      }

      if (Token::NAME_TYPE === $token->getType() && $token->getValue() === 'raw' &&
        ($ignoreRawLine + 1) !== $token->getLine()) {

        $violations[] = $this->createViolation(
          $tokens->getSourceContext()->getPath(),
          $token->getLine(),
          $token->getColumn(),
          'The use of "raw" is not allowed.'
        );
      }

      $tokens->next();
    }

    return $violations;
  }
}
