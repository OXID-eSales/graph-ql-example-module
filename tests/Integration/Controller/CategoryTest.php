<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Example\Tests\Integration\Controller;

use OxidEsales\GraphQL\Base\Tests\Integration\TestCase;
use OxidEsales\GraphQL\Example\Dao\CategoryDaoInterface;

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
            400,
            static::$queryResult['status']
        );
        $this->assertEquals(
            'There is no category with id \'does-not-exist\'.',
            static::$queryResult['body']['errors'][0]['debugMessage']
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
        $this->assertEquals(7, sizeof(static::$queryResult['body']['data']['categories']));
    }

    public function testCreateAndUpdateSimpleCategory()
    {
        // Login
        $this->execQuery('query { token (username: "admin", password: "admin") }');
        $this->setAuthToken(static::$queryResult['body']['data']['token']);

        // Determine no of root categories
        $this->execQuery('query {categories {id}}');
        $noOfCategories = sizeof(static::$queryResult['body']['data']['categories']);

        // Create category
        $this->execQuery('mutation { categoryCreateOrUpdate(category: {name: "foobar"}) {id, name} }');
        $this->assertEquals(
            200,
            static::$queryResult['status']
        );
        $this->assertEquals(
            'foobar',
            static::$queryResult['body']['data']['categoryCreateOrUpdate']['name']
        );
        // Get id for newly created category
        $id = static::$queryResult['body']['data']['categoryCreateOrUpdate']['id'];

        // Verify that there is one more root category
        $this->execQuery('query {categories {id}}');
        $this->assertEquals($noOfCategories + 1, sizeof(static::$queryResult['body']['data']['categories']));

        // Update category
        $this->execQuery('mutation { categoryCreateOrUpdate(category: {name: "barfoo", id: "' .
            $id . '"}) {id, name} }');
        $this->assertEquals(
            200,
            static::$queryResult['status']
        );
        $this->assertEquals(
            'barfoo',
            static::$queryResult['body']['data']['categoryCreateOrUpdate']['name']
        );
        $this->assertEquals($id, static::$queryResult['body']['data']['categoryCreateOrUpdate']['id']);

        // Verify that there are exactly the same number of root categories
        $this->execQuery('query {categories {id}}');
        $this->assertEquals($noOfCategories + 1, sizeof(static::$queryResult['body']['data']['categories']));

        // Fetch the new category
        $this->execQuery("query { category (id: \"$id\") {id, name, children { id }, parent { id }}}");
        $this->assertEquals(
            200,
            static::$queryResult['status']
        );
        $this->assertEquals(
            'barfoo',
            static::$queryResult['body']['data']['category']['name']
        );
        $this->assertEquals(
            [],
            static::$queryResult['body']['data']['category']['children']
        );
        $this->assertNull(
            static::$queryResult['body']['data']['category']['parent']
        );
    }


    public function testCreateSubCategory()
    {
        $this->execQuery('query { token (username: "admin", password: "admin") }');
        $this->setAuthToken(static::$queryResult['body']['data']['token']);

        $this->execQuery('mutation { categoryCreateOrUpdate(category: {name: "foobar1"}) {id, name} }');
        $parentid = static::$queryResult['body']['data']['categoryCreateOrUpdate']['id'];
        $this->assertNotNull($parentid);

        $this->execQuery(
            "mutation { categoryCreateOrUpdate(category: " .
            "{name: \"foobar2\", parentid: \"$parentid\"}) {id, name, parent{id}} }"
        );
        $this->assertEquals(
            200,
            static::$queryResult['status']
        );
        $this->assertEquals($parentid, static::$queryResult['body']['data']['categoryCreateOrUpdate']['parent']['id']);
    }
}
