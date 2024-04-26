<?php

/**
 * Created by dsmaga at 26.04.2024
 */

declare(strict_types=1);

namespace HelloKitty\Application\Command\CreateKitty;

use App\HelloKitty\Application\Command\CreateKitty\CreateKittyService;
use App\HelloKitty\Domain\ValueObject\KittyId;
use App\HelloKitty\Infrastructure\Repository\KittyRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\UuidV4;

class CreateKittyServiceTest extends KernelTestCase
{
    public function testCreate(): void
    {
        $kernel = self::bootKernel([
            'environment' => 'test',
            'debug'       => false,
        ]);

        $databaseFile = static::$kernel?->getProjectDir() . '/var/test-data/kitties.json';

        if (file_exists($databaseFile)) {
            unlink($databaseFile);
        }

        register_shutdown_function(function () use ($databaseFile) {
            if (file_exists($databaseFile)) {
                unlink($databaseFile);
            }
        });

        $repository = new KittyRepository($databaseFile);
        $createKittyService = new CreateKittyService($repository);
        $id = (string)UuidV4::v4();
        $createKittyService->create($id, 'KittyName');

        $this->assertTrue($repository->exists(new KittyId($id)));

        $this->assertFileExists($databaseFile);

        $json = file_get_contents($databaseFile);

        $this->assertNotFalse($json);
        $this->assertTrue(json_validate($json));

        $data = json_decode($json, true);

        $this->assertIsArray($data);
        $this->assertCount(1, $data);

        $this->assertEquals($id, $data[0]['aggregateId']);
        $this->assertEquals('KittyName', $data[0]['data']['name']);

        unlink($databaseFile);
    }
}
