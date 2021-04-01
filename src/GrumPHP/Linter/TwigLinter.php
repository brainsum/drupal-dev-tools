<?php

namespace Brainsum\DrupalDevTools\GrumPHP\Linter;

use Brainsum\DrupalDevTools\TwigLinter\LintCommand;
use Brainsum\DrupalDevTools\TwigLinter\SimpleString;
use Exception;
use GrumPHP\Collection\LintErrorsCollection;
use GrumPHP\Linter\LinterInterface;
use SplFileInfo;
use function class_exists;

/**
 * Twig linter service for GrumPHP.
 *
 * @package Brainsum\DrupalDevTools\GrumPHP\Linter
 */
class TwigLinter implements LinterInterface {

  /**
   * Twig linter command.
   *
   * @var \Brainsum\DrupalDevTools\TwigLinter\LintCommand
   */
  protected $linter;

  /**
   * TwigLinter constructor.
   */
  public function __construct() {
    $this->linter = new LintCommand();
  }

  /**
   * {@inheritdoc}
   */
  public function lint(SplFileInfo $file): LintErrorsCollection {
    $errors = new LintErrorsCollection();
    return $errors;

    $lintResults = [];

    try {
      $lintResults = $this->linter->run($file->getPathname());
    }
    catch (Exception $exception) {
      // @todo: What to do with this?
      $errors->add(new SimpleString($exception->getMessage()));
    }

    if ($lintResults['failed'] > 0) {
      foreach ($lintResults['errors'] as $lintError) {
        $errors->add(new SimpleString($lintError));
      }
    }

    return $errors;
  }

  /**
   * {@inheritdoc}
   */
  public function isInstalled(): bool {
    return FALSE;
    // return class_exists(LintCommand::class);
  }

}
