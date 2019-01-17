<?php

namespace Brainsum\DrupalDevTools\GrumPHP\Task;

use GrumPHP\Runner\TaskResult;
use GrumPHP\Runner\TaskResultInterface;
use GrumPHP\Task\AbstractLinterTask;
use GrumPHP\Task\Context\ContextInterface;
use GrumPHP\Task\Context\GitPreCommitContext;
use GrumPHP\Task\Context\RunContext;

/**
 * Twig Lint task for GrumPHP.
 *
 * @package Brainsum\DrupalDevTools\GrumPHP\Task
 */
class TwigLint extends AbstractLinterTask {

  /**
   * {@inheritdoc}
   */
  public function getName(): string {
    return 'twig_lint';
  }

  /**
   * {@inheritdoc}
   */
  public function canRunInContext(ContextInterface $context): bool {
    return ($context instanceof GitPreCommitContext || $context instanceof RunContext);
  }

  /**
   * {@inheritdoc}
   */
  public function run(ContextInterface $context): TaskResultInterface {
    /** @var array $config */
    $config = $this->getConfiguration();
    $whitelistPatterns = $config['whitelist_patterns'] ?? [];
    $extensions = '/\.(twig)$/i';

    /** @var \GrumPHP\Collection\FilesCollection $files */
    $files = $context->getFiles()->name($extensions);
    if (\count($whitelistPatterns) >= 1) {
      $files = $context->getFiles()
        ->paths($whitelistPatterns)
        ->name($extensions);
    }
    if (0 === count($files)) {
      return TaskResult::createSkipped($this, $context);
    }

    try {
      $lintErrors = $this->lint($files);
    }
    catch (\RuntimeException $e) {
      return TaskResult::createFailed($this, $context, $e->getMessage());
    }

    if ($lintErrors->count()) {
      return TaskResult::createFailed($this, $context, (string) $lintErrors);
    }

    return TaskResult::createPassed($this, $context);
  }

}
