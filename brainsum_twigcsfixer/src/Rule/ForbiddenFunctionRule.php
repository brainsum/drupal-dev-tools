<?php

namespace Brainsum\TwigCsFixer\Rule;

use TwigCsFixer\Rules\AbstractRule;
use TwigCsFixer\Rules\ConfigurableRuleInterface;
use TwigCsFixer\Rules\RuleInterface;
use TwigCsFixer\Token\Token;
use TwigCsFixer\Token\Tokens;

/**
 * Rule to detect forbidden functions like dump(), kint(), etc.
 */
class ForbiddenFunctionRule extends AbstractRule implements RuleInterface, ConfigurableRuleInterface
{
  /**
   * List of forbidden function names.
   *
   * @var array
   */
  private $forbiddenFunctions;

  /**
   * ForbiddenFunctionRule constructor.
   *
   * @param array $functions
   *   List of forbidden function names.
   */
  public function __construct(array $functions = ['dump', 'dpm', 'kint', 'print_r', 'var_dump', 'var_export', 'vardumper'])
  {
    $this->forbiddenFunctions = array_map('strtolower', $functions);
  }

  /**
   * {@inheritdoc}
   */
  public function getConfiguration(): array
  {
    return [
      'functions' => $this->forbiddenFunctions,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function process(int $tokenIndex, Tokens $tokens): void
  {
    $token = $tokens->get($tokenIndex);

    // Check if token value is a forbidden function name
    $functionName = strtolower($token->getValue());
    if (in_array($functionName, $this->forbiddenFunctions)) {
      $this->addError(
        sprintf('The use of "%s()" function is not allowed.', $token->getValue()),
        $token
      );
    }
  }
  
  /**
   * {@inheritdoc}
   */
  public function getName(): string
  {
    return 'forbidden_function';
  }
}
