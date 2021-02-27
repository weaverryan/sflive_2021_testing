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
            ->assertSuccessful();

        $this->assertSame('Slightly old popcorn', $product->getName());
    }

    public function testSearchAutoSuggestion(): void
    {
        ProductFactory::createOne(['name' => 'Floppy Disk']);
        ProductFactory::createOne(['name' => 'Compact Disk']);
        $this->pantherBrowser()
            ->visit('/')
            ->fillField('q', 'dis')
            ->waitUntilSeeIn('.search-preview', 'Floppy Disk')
            ->assertElementCount('.search-preview .list-group-item', 2);
    }
}
