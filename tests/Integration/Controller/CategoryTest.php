<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Example\Tests\Integration\Controller;

use OxidEsales\GraphQL\Base\Tests\Integration\TestCase;

class CategoryTest extends TestCase
{
    public function testGetSingleCategoryWithoutParam()
    {
        $this->execQuery('query { category }');
        $this->assertEquals(
            400,
            static::$queryResult['status']
        );
    }

    public function testGetSingleCategoryWithNonExistantCategoryId()
    {
        $this->execQuery('query { category (id: "does-not-exist"){id, name}}');
        $this->assertEquals(
            200,
            static::$queryResult['status']
        );
        $this->assertNull(
            static::$queryResult['body']['data']['category']
        );
    }

    public function testGetCategorieListWithoutParams()
    {
        $this->execQuery('query { categories {id, name}}');
        $this->assertEquals(
            200,
            static::$queryResult['status']
        );
    }

    public function testCreateSimpleCategory()
    {
        $this->execQuery('query { token (username: "admin", password: "admin") }');
        $this->setAuthToken(static::$queryResult['body']['data']['token']);
        $this->execQuery('mutation { categoryCreate(category: {id: "10", name: "foobar"}) {id, name} }');
        $this->assertEquals(
            200,
            static::$queryResult['status']
        );
        $this->assertEquals(
            'foobar',
            static::$queryResult['body']['data']['categoryCreate']['name']
        );
    }

    /**
     * @depends testCreateSimpleCategory
     */
    public function testGetSimpleCategoryJustCreatedById()
    {
        $this->execQuery('query { category (id: "10") {id, name}}');
        $this->assertEquals(
            200,
            static::$queryResult['status']
        );
        $this->assertEquals(
            'foobar',
            static::$queryResult['body']['data']['category']['name']
        );
    }

    /**
     * @depends testCreateSimpleCategory
     */
    public function testGetSimpleCategoryJustCreated()
    {
        $this->execQuery('query { categories {id, name}}');
        $this->assertEquals(
            200,
            static::$queryResult['status']
        );
        $this->assertEquals(
            'foobar',
            static::$queryResult['body']['data']['categories'][0]['name']
        );
    }

    /**
     * @depends testCreateSimpleCategory
     */
    public function testGetSimpleCategoryJustCreatedWithExtras()
    {
        $this->execQuery('query { categories {id, name, children { id }, parent { id }}}');
        $this->assertEquals(
            200,
            static::$queryResult['status']
        );
        $this->assertEquals(
            'foobar',
            static::$queryResult['body']['data']['categories'][0]['name']
        );
        $this->assertEquals(
            [],
            static::$queryResult['body']['data']['categories'][0]['children']
        );
        $this->assertNull(
            static::$queryResult['body']['data']['categories'][0]['parent']
        );
    }
}
