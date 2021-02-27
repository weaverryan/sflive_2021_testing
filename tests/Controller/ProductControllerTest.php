<?php

namespace App\Tests\Controller;

use App\Factory\ProductFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class ProductControllerTest extends WebTestCase
{
    use Factories, ResetDatabase;

    public function testEditProduct(): void
    {
        $client = static::createClient();
        $product = ProductFactory::createOne(['name' => 'Brand new popcorn']);
        $crawler = $client->request('GET', sprintf('/product/%s/edit', $product->getId()));

        $client->submitForm('Update', [
            'product[name]' => 'Slightly old popcorn'
        ]);
        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertSame('Slightly old popcorn', $product->getName());
    }
}
