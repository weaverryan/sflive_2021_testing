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
        $product = ProductFactory::createOne(['name' => 'Brand new popcorn'])
            ->enableAutoRefresh();
        $crawler = $client->request('GET', sprintf('/product/%s/edit', $product->getId()));

        $client->submitForm('Update', [
            'product[name]' => 'Slightly old popcorn'
        ]);
        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertSame('Slightly old popcorn', $product->getName());
    }

    public function testSearchAutoSuggestion(): void
    {
        $client = static::createClient();
        ProductFactory::createOne(['name' => 'Floppy Disk']);
        ProductFactory::createOne(['name' => 'Compact Disk']);
        $crawler = $client->request('GET', '/');

        $searchForm = $crawler->selectButton('Search')->form();
        $searchForm->setValues(['q' => 'dis']);

        $this->assertCount(
            2,
            $crawler->filter('.search-preview .list-group-item')
        );
    }
}
