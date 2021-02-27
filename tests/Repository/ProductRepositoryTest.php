<?php

namespace App\Tests\Repository;

use App\Factory\ProductFactory;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class ProductRepositoryTest extends KernelTestCase
{
    use Factories, ResetDatabase;

    public function testSearch(): void
    {
        $kernel = self::bootKernel();

        $productRepository = self::$container->get(ProductRepository::class);
        $this->assertInstanceOf(ProductRepository::class, $productRepository);

        ProductFactory::createOne(['name' => 'floppy disk']);
        ProductFactory::createOne(['name' => 'popcorn']);
        ProductFactory::createOne(['description' => 'A CD (compact disk)']);

        $results = $productRepository->search('disk');
        $this->assertCount(2, $results);
    }
}
