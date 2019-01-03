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
   * @param string $path
   *   Name of the file.
   *
   * @return array
   *   An array of errors, if any.
   */
  public function run(string $path): array {
    if (!$path) {
      throw new \RuntimeException('Please provide a filename.');
    }

    if (!\is_readable($path)) {
      throw new \RuntimeException(\sprintf('File or directory "%s" is not readable', $path));
    }

    $linted = 0;
    $failed = 0;
    $errors = [];

    foreach ($this->fetchFiles($path) as $file) {
      ++$linted;
      $lintErrors = $this->validate(\file_get_contents($file), $file);

      if (!empty($lintErrors)) {
        ++$failed;
        $filePath = $file->getPathname();
        $message = "The '$filePath' template is invalid\n";
        $message .= \implode("\n", $lintErrors);
        $errors[] = $message;
      }
    }

    return [
      'linted' => $linted,
      'failed' => $failed,
      'errors' => $errors,
    ];
  }

  /**
   * Parse the input for files.
   *
   * @param string $input
   *   File or directory path.
   * @param array $exclude
   *   Exclude array.
   *
   * @return \SplFileInfo[]
   *   An array of SplFileInfo objects.
   */
  protected function fetchFiles(string $input, array $exclude = []): array {
    /** @var \SplFileInfo[] $files */
    $files = [];
    if (\is_file($input)) {
      $files = [new \SplFileInfo($input)];
    }
    elseif (\is_dir($input)) {
      $files = Finder::create()->files()->in($input)->name('*.twig')->filter(
        function (\SplFileInfo $file) use ($exclude) {
          foreach ($exclude as $excludeItem) {
            if (1 === \preg_match('#' . $excludeItem . '#', $file->getRealPath())) {
              return FALSE;
            }
          }
          return TRUE;
        }
      );
    }

    return $files;
  }

  /**
   * Validate a template.
   *
   * @param string $template
   *   The template as a string.
   * @param \SplFileInfo|null $file
   *   (Optional) The file.
   *
   * @return string[]
   *   An array of errors, if any.
   */
  protected function validate($template, $file = NULL): array {
    $errors = [];
    $filename = $file ? (string) $file : NULL;

    try {
      // @todo: Return every error, not just the first one.
      // @todo: Investigate CatchAll, that should do that already.
      $this->twig->parse($this->twig->tokenize($template, $filename));
    }
    catch (\Twig_Error $e) {
      $lineOfError = $e->getTemplateLine();
      $lintMessage = $e->getRawMessage();

      $errors[] = "\tLine $lineOfError: $lintMessage";
    }

    return $errors;
  }

}
