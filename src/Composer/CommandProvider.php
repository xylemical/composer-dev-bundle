<?php

namespace Xylemical\Development\Composer;

use Composer\Plugin\Capability\CommandProvider as CommandProviderCapability;

/**
 * Provides additional commands to composer.
 */
class CommandProvider implements CommandProviderCapability {

  /**
   * {@inheritdoc}
   */
  public function getCommands() {
    return [
      new TestCommand()
    ];
  }

}
