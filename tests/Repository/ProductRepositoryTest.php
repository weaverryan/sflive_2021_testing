<?php

namespace App\Tests\Repository;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProductRepositoryTest extends KernelTestCase
{
    public function testSearch(): void
    {
        $kernel = self::bootKernel();

        $productRepository = self::$container->get(ProductRepository::class);
        $this->assertInstanceOf(ProductRepository::class, $productRepository);
    }
}
