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
    $token = $tokens->get($tokenIndex);
    
    // Only process TEXT tokens
    if (Token::TEXT_TYPE !== $token->getType()) {
      return;
    }
    
    $content = $token->getValue();
    
    // Check for patterns like foo={{ var }} or foo= {{ var }}
    if (str_ends_with(trim($content), '=')) {
      // Check if next token is a variable start
      $nextIndex = $tokenIndex + 1;
      $nextToken = $tokens->get($nextIndex);
      
      // Skip whitespace tokens
      while ($nextToken && $nextToken->getType() === Token::WHITESPACE_TYPE) {
        $nextIndex++;
        $nextToken = $tokens->offsetExists($nextIndex) ? $tokens->get($nextIndex) : null;
      }
      
      if ($nextToken && $nextToken->getType() === Token::VAR_START_TYPE) {
        // Count quotes to determine if we're inside an attribute value
        $quoteCount = substr_count(strstr($content, '<'), '"');
        
        // If quote count is even, we're likely not inside quoted attribute value
        if ($quoteCount % 2 === 0) {
          $this->addError(
            'Unsafe attribute value without quotation mark.',
            $token
          );
        }
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
