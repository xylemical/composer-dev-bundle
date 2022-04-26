<?php

namespace Xylemical\Development\Composer;

use Composer\Command\BaseCommand;
use Composer\IO\BufferIO;
use Composer\IO\ConsoleIO;
use Composer\Util\ProcessExecutor;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Provides the command for testing.
 */
class TestCommand extends BaseCommand {

  /**
   * The required tests.
   */
  protected const REQUIRED = [
    'phpunit' => 'phpunit.xml',
    'phpstan' => 'phpstan.neon',
    'phpcs' => 'phpcs.xml',
  ];

  /**
   * The optional tests/helpers.
   */
  protected const OPTIONAL = [
    'phpcbf' => 'phpcs.xml',
  ];

  /**
   * {@inheritdoc}
   */
  protected function configure() {
    $this->setName('test');

    $description = 'The required tests are: ' . implode(', ', static::REQUIRED);
    $description .= "\nOther tests that can be run: " . implode(', ', static::OPTIONAL);

    $this->addArgument('targets', InputArgument::OPTIONAL, $description);
  }

  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output) {
    $targets = explode(',', $input->getArgument('targets'));
    if (empty($targets)) {
      $targets = array_keys(static::REQUIRED);
    }

    $configs = static::REQUIRED + static::OPTIONAL;
    foreach ($targets as $target) {
      if ($configs[$target]) {
        if ($result = $this->testTarget($target, $configs[$target])) {
          return $result;
        }
      }
    }

    return 0;
  }

  /**
   * Get the configuration file.
   *
   * @param string $configuration
   *   The filename.
   *
   * @return string
   *   The real location of the configuration file.
   */
  protected function getConfiguration(string $configuration): string {
    return realpath(__DIR__ . "/../{$configuration}");
  }

  /**
   * Get the command based on the binary directory.
   *
   * @param string $command
   *   The command.
   *
   * @return string
   *   The full command path.
   */
  protected function getCmd(string $command): string {
    $composer = $this->requireComposer();
    $bin_dir = $composer->getConfig()->get('bin-dir');
    return realpath("{$bin_dir}/{$command}");
  }

  /**
   * Perform the test using the target.
   *
   * @param string $target
   *   The targets.
   * @param string $config
   *   The configuration file for the target.
   *
   * @return int
   */
  protected function testTarget(string $target, string $config): int {
    $process = new ProcessExecutor($this->getIO());

    $path = vsprintf("%s -c %s", [
      $this->getCmd($target),
      $this->getConfiguration($config)
    ]);

    $output = [];
    $result = $process->execute($path, $output, getcwd());

    return $result;
  }

}
