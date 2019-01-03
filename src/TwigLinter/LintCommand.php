<?php

namespace Brainsum\DrupalDevTools\TwigLinter;

use Symfony\Component\Finder\Finder;

/**
 * Command to lint twig files.
 *
 * @see: https://github.com/asm89/twig-lint/blob/master/src/Asm89/Twig/Lint/Command/LintCommand.php
 * @see: https://github.com/symfony/symfony/blob/6b66bc3226fae6e0416039de75f47f91db56bfd9/src/Symfony/Bundle/TwigBundle/Command/LintCommand.php.
 */
class LintCommand {

  protected $twig;

  /**
   * LintCommand constructor.
   */
  public function __construct() {
    // @todo: The deprecated \Twig_Loader_String() was removed from the constructor.
    // @todo: Maybe $this->twig->createTemplate() has to be used somewhere.
    $this->twig = new StubbedEnvironment();
  }

  /**
   * Run the command.
   *
   * @param string $filename
   *   Name of the file.
   *
   * @return array
   *   An array of errors, if any.
   */
  public function run($filename): array {
    if (!$filename) {
      throw new \RuntimeException('Please provide a filename.');
    }

    if (!\is_readable($filename)) {
      throw new \RuntimeException(\sprintf('File or directory "%s" is not readable', $filename));
    }

    $exclude = [];

    $files = [];
    if (is_file($filename)) {
      $files = [$filename];
    }
    elseif (is_dir($filename)) {
      $files = Finder::create()->files()->in($filename)->name('*.twig')->filter(
        function (\SplFileInfo $file) use ($exclude) {
          foreach ($exclude as $excludeItem) {
            if (1 === preg_match('#' . $excludeItem . '#', $file->getRealPath())) {
              return FALSE;
            }
          }
          return TRUE;
        }
      );
    }

    $linted = 0;
    $errors = 0;
    $results = [];
    foreach ($files as $file) {
      ++$linted;
      $results = $this->validate(\file_get_contents($file), $file);

      if (!empty($results)) {
        ++$errors;
      }
    }

    return [
      'linted' => $linted,
      'errors' => $errors,
      'results' => $results,
    ];
  }

  /**
   * Validate a template.
   *
   * @param string $template
   *   The template as a string.
   * @param \SplFileInfo|null $file
   *   (Optional) The file.
   *
   * @return array
   *   An array of errors, if any.
   */
  protected function validate($template, $file = NULL): array {
    $errors = [];
    $filename = $file ? (string) $file : NULL;

    try {
      $this->twig->parse($this->twig->tokenize($template, $filename));
    }
    catch (\Twig_Error $e) {
      echo "---error---\n";
      echo "---error---\n";
      echo $e->getMessage();
      echo "---error---\n";
      echo "---error---\n";
      $errors = [
        'filename' => $filename,
        'errors' => $e,
      ];
    }

    return $errors;
  }

}
