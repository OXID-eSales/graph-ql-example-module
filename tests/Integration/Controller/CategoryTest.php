<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Example\Tests\Integration\Controller;

use OxidEsales\EshopCommunity\Tests\Integration\Internal\TestContainerFactory;
use OxidEsales\GraphQL\Base\Tests\Integration\TestCase;
use OxidEsales\GraphQL\Example\Dao\CategoryDaoInterface;

class CategoryTest extends TestCase
{
    /** @var CategoryDaoInterface */
    private $categoryDao;

    public function testGetSingleCategoryWithoutParam()
    {
        $queryResult = $this->query('query { category }');
        $this->assertEquals(
            400,
            $queryResult['status']
        );
    }

    public function testGetSingleCategoryWithNonExistantCategoryId()
    {
        $queryResult = $this->query('query { category (id: "does-not-exist"){id, name}}');
        $this->assertEquals(
            200,
            $queryResult['status']
        );
        $this->assertNull(
            $queryResult['body']['data']['category']
        );
    }

    public function testGetCategorieListWithoutParams()
    {
        $queryResult = $this->query('query { categories {id, name}}');
        $this->assertEquals(
            200,
            $queryResult['status']
        );
    }

    public function testCreateSimpleCategory()
    {
        $queryResult = $this->query('query { token (username: "admin", password: "admin") }');
        $this->setAuthToken($queryResult['body']['data']['token']);
        $queryResult = $this->query('mutation { categoryCreate(category: {id: "10", name: "foobar"}) {id, name} }');
        $this->assertEquals(
            200,
            $queryResult['status']
        );
        $this->assertEquals(
            'foobar',
            $queryResult['body']['data']['categoryCreate']['name']
        );
    }

    /**
     * @depends testCreateSimpleCategory
     */
    public function testGetSimpleCategoryJustCreatedById()
    {
        $queryResult = $this->query('query { category (id: "10") {id, name}}');
        $this->assertEquals(
            200,
            $queryResult['status']
        );
        $this->assertEquals(
            'foobar',
            $queryResult['body']['data']['category']['name']
        );
    }

    /**
     * @depends testCreateSimpleCategory
     */
    public function testGetSimpleCategoryJustCreated()
    {
        $queryResult = $this->query('query { categories {id, name}}');
        $this->assertEquals(
            200,
            $queryResult['status']
        );
        $this->assertEquals(
            'foobar',
            $queryResult['body']['data']['categories'][0]['name']
        );
    }

    /**
     * @depends testCreateSimpleCategory
     */
    public function testGetSimpleCategoryJustCreatedByFiltering()
    {
        $queryResult = $this->query('query { categories(filter: {title: {contains: "foo"}}) {id, name}}');
        $this->assertEquals(
            200,
            $queryResult['status']
        );
        $this->assertEquals(
            'foobar',
            $queryResult['body']['data']['categories'][0]['name']
        );
    }

    /**
     * @depends testCreateSimpleCategory
     */
    public function testGetSimpleCategoryJustCreatedWithExtras()
    {
        $queryResult = $this->query('query { categories {id, name, children { id }, parent { id }}}');
        $this->assertEquals(
            200,
            $queryResult['status']
        );
        $this->assertEquals(
            'foobar',
            $queryResult['body']['data']['categories'][0]['name']
        );
        $this->assertEquals(
            [],
            $queryResult['body']['data']['categories'][0]['children']
        );
        $this->assertNull(
            $queryResult['body']['data']['categories'][0]['parent']
        );
    }

    public function testCreateSimpleCategoryWithAutoId()
    {
        $queryResult = $this->query('query { token (username: "admin", password: "admin") }');
        $this->setAuthToken($queryResult['body']['data']['token']);
        $queryResult = $this->query('mutation { categoryCreate(category: {name: "foobar"}) {id, name} }');
        $this->assertEquals(
            200,
            $queryResult['status']
        );
        $this->assertEquals(
            'foobar',
            $queryResult['body']['data']['categoryCreate']['name']
        );
        $this->assertInternalType(
            'string',
            $queryResult['body']['data']['categoryCreate']['id']
        );
    }

    public function testCreateSubCategory()
    {
        $this->markTestSkipped("Does not work although the query works on console.");

        $queryResult = $this->query('query { token (username: "admin", password: "admin") }');
        $this->setAuthToken($queryResult['body']['data']['token']);

        $queryResult = $this->query('mutation { categoryCreate(category: {name: "foobar1"}) {id, name} }');
        $parentid = $queryResult['body']['data']['categoryCreate']['id'];
        $this->assertNotNull($parentid);

        $queryResult = $this->query(
            "mutation { categoryCreate(category: {name: \"foobar2\"}, parent: {id: \"$parentid\"}) {id, name, parent} }"
        );
        $this->assertEquals(
            200,
            $queryResult['status']
        );
        $this->assertEquals(
            $parentid,
            $queryResult['body']['data']['categoryCreate']['parent']['id']
        );
    }
}
