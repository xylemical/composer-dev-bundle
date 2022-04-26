<?php

namespace Xylemical\Development\Composer;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\Capability\CommandProvider as CommandProviderCapability;
use Composer\Plugin\Capable;
use Composer\Plugin\PluginInterface;
use Xylemical\Development\Composer\CommandProvider;

/**
 * Provides the installation of the development requirements.
 */
class Plugin implements PluginInterface, Capable {

  /**
   * {@inheritdoc}
   */
  public function activate(Composer $composer, IOInterface $io) {
  }

  /**
   * {@inheritdoc}
   */
  public function deactivate(Composer $composer, IOInterface $io) {
  }

  /**
   * {@inheritdoc}
   */
  public function uninstall(Composer $composer, IOInterface $io) {
  }

  /**
   * {@inheritdoc}
   */
  public function getCapabilities() {
    return [
      CommandProviderCapability::class => CommandProvider::class,
    ];
  }

}
