<?php

/**
 * Created by Dominik Smaga <dsmaga@gmail.com> at 25.04.2024 01:40.
 */

declare(strict_types=1);

namespace App\Tests;

use App\Kernel;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class KernelTest extends KernelTestCase
{
    public function testKernel(): void
    {
        $kernel = self::bootKernel([
            'environment' => 'test',
            'debug'       => false,
        ]);
        $this->assertInstanceOf(Kernel::class, $kernel);
        $this->assertTrue($kernel->isDebug() === false);
    }
}
