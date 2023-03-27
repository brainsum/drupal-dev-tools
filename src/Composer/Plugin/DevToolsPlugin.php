<?php

namespace Brainsum\DrupalDevTools\Composer\Plugin;

use Brainsum\DrupalDevTools\Composer\Command\Initialize;
use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Installer\PackageEvent;
use Composer\Installer\PackageEvents;
use Composer\IO\IOInterface;
use Composer\Package\PackageInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\ScriptEvents;

/**
 * Plugin implementation for the dev tools.
 *
 * This is needed, so we can execute scripts at install time.
 *
 * @package Brainsum\DrupalDevTools\Composer\Plugin
 *
 * @see https://github.com/phpro/grumphp/blob/master/src/Composer/GrumPHPPlugin.php
 */

/**
 * @psalm-suppress MissingConstructor
 */
class DevToolsPlugin implements PluginInterface, EventSubscriberInterface {

  private const PACKAGE_NAME = 'brainsum/drupal-dev-tools';

  /**
   * @var Composer
   */
  private $composer;

  /**
   * @var IOInterface
   */
  private $io;

  /**
   * @var bool
   */
  private $initScheduled = false;

  /**
   * {@inheritdoc}
   */
  public function activate(Composer $composer, IOInterface $io): void
  {
      $this->composer = $composer;
      $this->io = $io;
  }

  /**
   * {@inheritdoc}
   */
  public function deactivate(Composer $composer, IOInterface $io): void
  {
  }

  /**
   * {@inheritdoc}
   */
  public function uninstall(Composer $composer, IOInterface $io): void
  {
  }

  /**
   * Attach package installation events:.
   *
   * {@inheritdoc}
   */
  public static function getSubscribedEvents(): array
  {
    return [
        PackageEvents::POST_PACKAGE_INSTALL => 'postPackageInstall',
        PackageEvents::POST_PACKAGE_UPDATE => 'postPackageUpdate',
        ScriptEvents::POST_INSTALL_CMD => 'runScheduledTasks',
        ScriptEvents::POST_UPDATE_CMD => 'runScheduledTasks',
    ];
  }

  /**
   * When this package is installed, files are also initialized.
   *
   * @param \Composer\Installer\PackageEvent $event
   *   The post install event.
   */
  public function postPackageInstall(PackageEvent $event): void
  {
    /** @var \Composer\DependencyResolver\Operation\InstallOperation $operation */
    $operation = $event->getOperation();
    $package = $operation->getPackage();

    if (!$this->isDevToolsPackage($package)) {
      return;
    }

    $this->initScheduled = TRUE;
  }

  /**
   * Determine if the package is this package.
   *
   * @param \Composer\Package\PackageInterface $package
   *   The package to inspect.
   *
   * @return bool
   *   TRUE, if this is our package.
   */
  protected function isDevToolsPackage(PackageInterface $package): bool
  {
    return $package->getName() === static::PACKAGE_NAME;
  }

  /**
   * When this package is updated, files are also updated.
   *
   * @param \Composer\Installer\PackageEvent $event
   *   The post install event.
   */
  public function postPackageUpdate(PackageEvent $event): void
  {
    /** @var \Composer\DependencyResolver\Operation\UpdateOperation $operation */
    $operation = $event->getOperation();
    $package = $operation->getTargetPackage();

    if (!$this->isDevToolsPackage($package)) {
      return;
    }

    $this->initScheduled = TRUE;
  }

  /**
   * Run tasks which need to run.
   */
  public function runScheduledTasks(): void
  {
    if ($this->initScheduled) {
      $this->initDevTools();
    }
  }

  /**
   * Helper for initializing the package.
   */
  protected function initDevTools(): void
  {
    $initCommand = new Initialize();
    $initCommand->execute();
  }

}
