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
        $queryResult = $this->query('query { category (id: "does-not-exist"){id, title}}');
        $this->assertEquals(
            404,
            $queryResult['status']
        );
    }

    public function testGetCategoriesWithNonExistentParentId()
    {
        $queryResult = $this->query('query {
            categories(filter: { parentid: { equals: "oxrootid" } }) {
                id
                title
            }
        }');
        $this->assertEquals(
            200,
            $queryResult['status']
        );
        $this->assertEquals(
            [],
            $queryResult['body']['data']['categories']
        );
    }

    public function testCreateSimpleCategory()
    {
        $queryResult = $this->query('query { token (username: "admin", password: "admin") }');
        $this->setAuthToken($queryResult['body']['data']['token']);
        $queryResult = $this->query(
            'mutation {
                categoryCreate(
                    category: {
                        id: "10",
                        title: "foobar"
                    }
                ) {
                    id, title, timestamp
                }
            }'
        );
        $this->assertEquals(
            200,
            $queryResult['status']
        );
        $this->assertEquals(
            'foobar',
            $queryResult['body']['data']['categoryCreate']['title']
        );
    }

    /**
     * @depends testCreateSimpleCategory
     */
    public function testGetCategorieListWithoutParams()
    {
        $queryResult = $this->query('query { categories {id, title}}');
        $this->assertEquals(
            200,
            $queryResult['status']
        );
    }

    /**
     * @depends testCreateSimpleCategory
     */
    public function testGetCategoriesWithParentId()
    {
        $queryResult = $this->query('query {
            categories(filter: { parentid: { equals: "oxrootid" } }) {
                id
            }
        }');
        $this->assertEquals(
            200,
            $queryResult['status']
        );
        $this->assertCount(
            1,
            $queryResult['body']['data']['categories']
        );
        $this->assertEquals(
            10,
            $queryResult['body']['data']['categories'][0]['id']
        );
    }

    /**
     * @depends testCreateSimpleCategory
     */
    public function testGetSimpleCategoryJustCreatedById()
    {
        $queryResult = $this->query('query { category (id: "10") {id, title}}');
        $this->assertEquals(
            200,
            $queryResult['status']
        );
        $this->assertEquals(
            'foobar',
            $queryResult['body']['data']['category']['title']
        );
    }

    /**
     * @depends testCreateSimpleCategory
     */
    public function testGetSimpleCategoryJustCreated()
    {
        $queryResult = $this->query('query { categories {id, title}}');
        $this->assertEquals(
            200,
            $queryResult['status']
        );
        $this->assertEquals(
            'foobar',
            $queryResult['body']['data']['categories'][0]['title']
        );
    }

    /**
     * @depends testCreateSimpleCategory
     */
    public function testGetSimpleCategoryJustCreatedWithExtras()
    {
        $queryResult = $this->query('query { categories {id, title, children { id }, parent { id }}}');
        $this->assertEquals(
            200,
            $queryResult['status']
        );
        $this->assertEquals(
            'foobar',
            $queryResult['body']['data']['categories'][0]['title']
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
        $queryResult = $this->query('mutation { categoryCreate(category: {title: "foobar"}) {id, title} }');
        $this->assertEquals(
            200,
            $queryResult['status']
        );
        $this->assertEquals(
            'foobar',
            $queryResult['body']['data']['categoryCreate']['title']
        );
        $this->assertInternalType(
            'string',
            $queryResult['body']['data']['categoryCreate']['id']
        );
    }

    /**
     * @depends testCreateSimpleCategory
     */
    public function testCreateSubCategory()
    {
        $queryResult = $this->query('query { token (username: "admin", password: "admin") }');
        $this->setAuthToken($queryResult['body']['data']['token']);

        $queryResult = $this->query(
            'mutation {
                categoryCreate(
                    category: {
                        id: "20",
                        title: "foobar",
                        parentid: "10"
                    }
                ) {
                    parent {
                        id
                    }
                }
            }'
        );

        $this->assertEquals(
            200,
            $queryResult['status']
        );
        $this->assertEquals(
            "10",
            $queryResult['body']['data']['categoryCreate']['parent']['id']
        );
    }
}
