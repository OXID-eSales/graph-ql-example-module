<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Example\Tests\Integration\Controller;

use OxidEsales\GraphQL\Base\Tests\Integration\TestCase;

final class CategoryTest extends TestCase
{
    public function testGetSingleCategoryWithoutParam(): void
    {
        $queryResult = $this->query('query { category }');
        $this->assertEquals(
            400,
            $queryResult['status']
        );
    }

    public function testGetSingleCategoryWithNonExistantCategoryId(): void
    {
        $queryResult = $this->query('
            query {
                category (id: "does-not-exist") {
                    id,
                    title
                }
            }
        ');
        $this->assertEquals(
            404,
            $queryResult['status']
        );
    }

    public function testGetCategoriesWithNonExistentParentId(): void
    {
        $queryResult = $this->query('
            query {
                categories(filter: {
                    parentid: {
                        equals: "oxrootid"
                    }
                }) {
                    id
                    title
                }
            }
        ');
        $this->assertEquals(
            200,
            $queryResult['status']
        );
        $this->assertEquals(
            [],
            $queryResult['body']['data']['categories']
        );
    }

    public function testCreateSimpleCategory(): void
    {
        $queryResult = $this->query('
            query {
                token (username: "admin", password: "admin")
            }
        ');
        $this->setAuthToken($queryResult['body']['data']['token']);
        $queryResult = $this->query('
            mutation {
                categoryCreate(
                    category: {
                        id: "10",
                        title: "foobar"
                    }
                ) {
                    id, title, timestamp
                }
            }
        ');
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
    public function testGetCategorieListWithoutParams(): void
    {
        $queryResult = $this->query('
            query {
                categories {
                    id,
                    title
                }
            }
        ');
        $this->assertEquals(
            200,
            $queryResult['status']
        );
    }

    /**
     * @depends testCreateSimpleCategory
     */
    public function testGetCategoriesWithParentId(): void
    {
        $queryResult = $this->query('
            query {
                categories(filter: {
                    parentid: {
                        equals: "oxrootid"
                    }
                }) {
                    id
                }
            }
        ');
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
    public function testGetSimpleCategoryJustCreatedById(): void
    {
        $queryResult = $this->query('
            query {
                category (id: "10") {
                    id,
                    title,
                    url
                }
            }
        ');
        $this->assertEquals(
            200,
            $queryResult['status']
        );
        $this->assertEquals(
            'foobar',
            $queryResult['body']['data']['category']['title']
        );
        $this->assertStringMatchesFormat(
            'http%s/foobar/',
            $queryResult['body']['data']['category']['url']
        );
    }

    /**
     * @depends testCreateSimpleCategory
     */
    public function testGetSimpleCategoryJustCreated(): void
    {
        $queryResult = $this->query('
            query {
                categories {
                    id,
                    title
                }
            }
        ');
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
    public function testGetSimpleCategoryJustCreatedWithExtras(): void
    {
        $queryResult = $this->query('
            query {
                categories {
                    id,
                    title,
                    children {
                        id
                    },
                    parent {
                        id
                    }
                }
            }
        ');

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

    public function testCreateSimpleCategoryWithAutoId(): void
    {
        $queryResult = $this->query('
            query {
                token (username: "admin", password: "admin")
            }
        ');
        $this->setAuthToken($queryResult['body']['data']['token']);
        $queryResult = $this->query('
            mutation {
                categoryCreate(category: {
                    title: "foobar"
                }) {
                    id,
                    title
                }
            }
        ');
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
    public function testCreateSubCategory(): void
    {
        $queryResult = $this->query('
            query {
                token (username: "admin", password: "admin")
            }
        ');
        $this->setAuthToken($queryResult['body']['data']['token']);

        $queryResult = $this->query(
            'mutation {
                categoryCreate(
                    category: {
                        id: "20",
                        title: "foobaz",
                        parentid: "10"
                    }
                ) {
                    title
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
            'foobaz',
            $queryResult['body']['data']['categoryCreate']['title']
        );
        $this->assertEquals(
            '10',
            $queryResult['body']['data']['categoryCreate']['parent']['id']
        );
    }
}
