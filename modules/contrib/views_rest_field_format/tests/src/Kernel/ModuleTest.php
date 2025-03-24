<?php

declare(strict_types=1);

namespace Drupal\Tests\views_rest_field_format\Kernel;

use Drupal\KernelTests\Core\Entity\EntityKernelTestBase;

/**
 * Test module.
 */
class ModuleTest extends EntityKernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'views',
    'rest',
    'serialization',
  ];

  /**
   * Test that module can be installed.
   */
  public function testModuleInstall() {
    $this->installModule('views_rest_field_format');
  }

}
