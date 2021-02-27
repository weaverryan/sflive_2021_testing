<?php

namespace App\Tests\Controller;

use App\Factory\ProductFactory;
use Symfony\Component\Panther\PantherTestCase;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class ProductControllerTest extends PantherTestCase
{
    use Factories, ResetDatabase, HasBrowser;

    public function testEditProduct(): void
    {
        $product = ProductFactory::createOne(['name' => 'Brand new popcorn'])
            ->enableAutoRefresh();

        $this->browser()
            ->visit(sprintf('/product/%s/edit', $product->getId()))
            ->assertSuccessful()
            ->fillField('Name', 'Slightly old popcorn')
            ->click('Update')
            ->followRedirect()
            ->dd()
            ->assertSuccessful();

        $this->assertSame('Slightly old popcorn', $product->getName());
    }

    public function testSearchAutoSuggestion(): void
    {
        $client = static::createPantherClient();
        ProductFactory::createOne(['name' => 'Floppy Disk']);
        ProductFactory::createOne(['name' => 'Compact Disk']);
        $crawler = $client->request('GET', '/');

        $searchForm = $crawler->selectButton('Search')->form();
        $searchForm->setValues(['q' => 'dis']);

        $this->assertSelectorWillContain('.search-preview', 'Floppy Disk');
    }
}
