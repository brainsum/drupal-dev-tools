<?php

namespace Brainsum\DrupalDevTools\GrumPHP\Linter;

use GrumPHP\Collection\LintErrorsCollection;
use GrumPHP\Linter\LinterInterface;
use SplFileInfo;
use Brainsum\DrupalDevTools\TwigLinter\LintCommand;

/**
 * Twig linter service for GrumPHP.
 *
 * @package Brainsum\DrupalDevTools\GrumPHP\Linter
 */
class TwigLinter implements LinterInterface {

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

    $lintResults = [];
    try {
      $lintResults = $this->linter->run($file);
    }
    catch (\Exception $exception) {
      // @todo: Parse the exception.
      $errors->add($exception);
    }

    if ($lintResults['errors'] > 0) {
      // @todo: Return errors instead.
      $errors->add($file->getFilename() . ' is not valid twig.');
    }

    return $errors;
  }

  /**
   * {@inheritdoc}
   */
  public function isInstalled(): bool {
    return \class_exists(LintCommand::class);
  }

}
