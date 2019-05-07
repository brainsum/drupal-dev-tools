<?php

namespace Brainsum\DrupalDevTools\Composer\Command;

use Composer\IO\ConsoleIO;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Filesystem\Filesystem;
use function getcwd;
use function str_replace;

/**
 * Initialization command.
 *
 * @package Brainsum\DrupalDevTools\Composer\Command
 */
class Initialize {

  /**
   * The names of the dist files.
   */
  public const DIST_FILES = [
    'grumphp.yml',
    'phpcs.xml',
  ];

  /**
   * The filesystem helper.
   *
   * @var \Symfony\Component\Filesystem\Filesystem
   */
  protected $fileSystem;

  /**
   * The project root dir.
   *
   * @var string
   */
  protected $projectRoot;

  /**
   * The package root dir.
   *
   * @var string
   */
  protected $packageRoot;

  /**
   * Console IO.
   *
   * @var \Composer\IO\ConsoleIO
   */
  protected $console;

  /**
   * Initialize constructor.
   */
  public function __construct() {
    $this->fileSystem = new Filesystem();
    $this->projectRoot = getcwd();
    // @todo: Maybe this needs to be something more robust, but it should be OK
    // for now.
    $this->packageRoot = static::normalizePath("$this->projectRoot/vendor/brainsum/drupal-dev-tools");
    $this->console = new ConsoleIO(new StringInput(''), new ConsoleOutput(), new HelperSet());
  }

  /**
   * Normalize a path.
   *
   * @param string $path
   *   The path.
   *
   * @return string
   *   The normalized path.
   */
  protected static function normalizePath(string $path): string {
    return str_replace('/', DIRECTORY_SEPARATOR, $path);
  }

  /**
   * Execute the command.
   */
  public function execute(): void {
    foreach (static::DIST_FILES as $file) {
      $this->copyDistFile($file);
    }
  }

  /**
   * Copy dist file to the project root.
   *
   * @param string $file
   *   The file.
   */
  protected function copyDistFile(string $file): void {
    if ($this->fileSystem->exists("$this->projectRoot/$file")) {
      $this->console->info("\t$file already exists in the target folder. You have to updated its contents manually.");
      return;
    }

    $this->console->write("Trying to create $file");
    $this->fileSystem->copy(
      static::normalizePath("$this->packageRoot/distfiles/$file"),
      static::normalizePath("$this->projectRoot/$file")
    );
  }

}
