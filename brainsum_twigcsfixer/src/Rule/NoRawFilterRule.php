<?php

namespace Brainsum\TwigCsFixer\Rule;

use TwigCsFixer\Rules\AbstractRule;
use TwigCsFixer\Rules\RuleInterface;
use TwigCsFixer\Token\Token;
use TwigCsFixer\Token\Tokens;

class NoRawFilterRule extends AbstractRule implements RuleInterface
{
  /**
   * {@inheritdoc}
   */
  public function process(int $tokenIndex, Tokens $tokens): void
  {
    $token = $tokens->get($tokenIndex);
    
    // Check for tokens with value 'raw'
    if ($token->getValue() === 'raw') {
      // Check if previous token is a filter operator '|'
      $prevIndex = $tokenIndex - 1;
      
      // Skip whitespace
      while ($prevIndex >= 0) {
        $prevToken = $tokens->get($prevIndex);
        // Skip whitespace tokens (type 14)
        if ($prevToken->getType() !== 14) {
          break;
        }
        $prevIndex--;
      }
      
      if ($prevIndex >= 0 && $tokens->get($prevIndex)->getValue() === '|') {
        // Check if there's an ignore comment on the current line
        $ignoreRaw = false;
        $currentLine = $token->getLine();
        
        // Look for ignore comment on the current line or previous line
        // We'll check a limited number of tokens before the current one
        $maxTokensToCheck = 20;
        $tokensChecked = 0;
        
        for ($i = $tokenIndex - 1; $i >= 0 && $tokensChecked < $maxTokensToCheck; $i--, $tokensChecked++) {
          $prevToken = $tokens->get($i);
          
          // If we've gone back more than one line, stop searching
          if ($prevToken->getLine() < $currentLine - 1) {
            break;
          }
          
          // Check if token contains our ignore comment
          $value = $prevToken->getValue();
          if ($value && strpos($value, '@TwigCsIgnoreNoRawFilterRule') !== false) {
            $ignoreRaw = true;
            break;
          }
        }
        
        if (!$ignoreRaw) {
          $this->addError(
            'The use of "raw" filter is not allowed.',
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
    return 'no_raw_filter';
  }
}
