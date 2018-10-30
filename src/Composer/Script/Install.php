<?php

namespace Brainsum\DrupalDevTools\Composer\Script;

use Brainsum\DrupalDevTools\Composer\Command\Initialize;

/**
 * Routines which should run on install or update.
 *
 * @package Brainsum\DrupalDevTools\Script
 */
class Install {

  /**
   * Setup routines.
   */
  public static function setup() {
    $initCommand = new Initialize();
    $initCommand->execute();
  }

}
