<?php

namespace Brainsum\TwigCsFixer\Rule;

use TwigCsFixer\Rules\AbstractRule;
use TwigCsFixer\Rules\RuleInterface;
use TwigCsFixer\Token\Token;
use TwigCsFixer\Token\Tokens;

class NoQuotationMarkAttributeRule extends AbstractRule implements RuleInterface
{
  /**
   * {@inheritdoc}
   */
  public function process(int $tokenIndex, Tokens $tokens): void
  {
    // Only process if this is a valid token
    if (!$tokens->has($tokenIndex)) {
      return;
    }

    $token = $tokens->get($tokenIndex);
    $tokenValue = $token->getValue();

    // Check for HTML attribute assignments without quotes
    if ($tokenValue === '=') {
      // Look ahead to see if the next token is a Twig variable
      $nextIndex = $tokenIndex + 1;

      // Skip whitespace
      while ($tokens->has($nextIndex)) {
        $nextToken = $tokens->get($nextIndex);
        // Skip whitespace tokens (type 14)
        if ($nextToken->getType() !== Token::WHITESPACE_TYPE) {
          break;
        }
        $nextIndex++;
      }

      // Check if the next token is a Twig variable start ({{)
      if ($tokens->has($nextIndex) && $tokens->get($nextIndex)->getValue() === '{{') {
        // This is an unsafe attribute value without quotation mark
        $this->addError(
          'Unsafe attribute value without quotation mark.',
          $token
        );
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getName(): string
  {
    return 'no_quotation_mark_attribute';
  }
}
