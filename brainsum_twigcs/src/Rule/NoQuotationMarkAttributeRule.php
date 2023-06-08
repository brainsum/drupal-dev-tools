<?php

namespace Brainsum\Twigcs\Rule;

use FriendsOfTwig\Twigcs\Rule\AbstractRule;
use FriendsOfTwig\Twigcs\Rule\RuleInterface;
use FriendsOfTwig\Twigcs\TwigPort\Token;
use FriendsOfTwig\Twigcs\TwigPort\TokenStream;
use FriendsOfTwig\Twigcs\Lexer;

class NoQuotationMarkAttributeRule extends AbstractRule implements RuleInterface
{
  public function check(TokenStream $tokens)
  {

    $violations = [];

    while (!$tokens->isEOF()) {
      $token = $tokens->getCurrent();

      // ={{ var }} || = {{ var }}
      if (Token::TEXT_TYPE === $token->getType() && str_ends_with(trim($token->getValue()), '=') &&
        Token::VAR_START_TYPE === $tokens->look(Lexer::NEXT_TOKEN)->getType() &&
        Token::WHITESPACE_TYPE === $tokens->look(2)->getType() &&
        Token::NAME_TYPE === $tokens->look(3)->getType()) {
            $quots = substr_count(strstr($token->getValue(), '<'), '"');

            if (!($quots % 2)) {
              $violations[] = $this->createViolation(
              $tokens->getSourceContext()->getPath(),
              $tokens->look(Lexer::NEXT_TOKEN)->getLine(),
              $tokens->look(Lexer::NEXT_TOKEN)->getColumn() + strlen($tokens->look(Lexer::NEXT_TOKEN)->getValue()) - 1,
              'Unsafe attribute value without quotation mark.'
              );
            }
        }

      $tokens->next();
    }

    return $violations;
  }
}
