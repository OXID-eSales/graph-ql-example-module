<?php declare(strict_types=1);

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\GraphQl\Sample\Tests\Acceptance;

use OxidEsales\GraphQl\Sample\Dao\CategoryDaoInterface;
use OxidEsales\GraphQl\Tests\Acceptance\BaseAcceptanceTestCase;

class CategoryTest extends BaseAcceptanceTestCase
{

    private $rootId;

    private $subId1;

    private $subId2;

    public function setUp()
    {
        parent::setUp();
        /** @var CategoryDaoInterface $categoryDao */
        $categoryDao = $this->container->get(CategoryDaoInterface::class);
        $this->rootId = $categoryDao->addCategory(["rootcategory"], $this->getShopId());
        $this->subId1 = $categoryDao->addCategory(["subcategory1"], $this->getShopId(), $this->rootId);
        $this->subId2 = $categoryDao->addCategory(["subcategory2"], $this->getShopId(), $this->rootId);
    }

    public function testGetCategory()
    {

        $query = <<<EOQ
query TestQuery { 
    category (categoryid: "$this->subId1") {
        name
    }
}
EOQ;
        $this->executeQuery($query);

        $this->assertEquals(200, $this->httpStatus);
        $this->assertEquals('subcategory1', $this->queryResult['data']['category']['name']);
    }

    public function testNotFound()
    {

        $query = <<<EOQ
query TestQuery { 
    category (categoryid: "nonexistingid") {
        name
    }
}
EOQ;
        $this->executeQuery($query);

        $this->assertEquals(400, $this->httpStatus);
        $this->assertEquals(
            'Category with id "nonexistingid" not found.',
            $this->queryResult['errors'][0]['message']);
        $this->assertTrue(0 === strpos($this->logResult, 'Category with id "nonexistingid" not found.'));
    }

    public function testGetRootCategories()
    {

        $query = <<<EOQ
query TestQuery { 
    categories {
        name
    }
}
EOQ;
        $this->executeQuery($query);

        $this->assertEquals(200, $this->httpStatus);
        $found = false;
        foreach ($this->queryResult['data']['categories'] as $categoryArray) {
            if ($categoryArray['name'] == 'rootcategory') {
                $found = true;
            }
        }
        $this->assertTrue($found);
    }

    public function testGetCategories()
    {

        $query = <<<EOQ
query TestQuery { 
    categories (parentid: "$this->rootId") {
        name
    }
}
EOQ;
        $this->executeQuery($query);

        $this->assertEquals(200, $this->httpStatus);
        $this->assertEquals(2, sizeof($this->queryResult['data']['categories']));
    }

    public function testAddCategory()
    {

        $query = <<<EOQ
mutation TestMutation { 
    addCategory (names: ["Neue Kategorie", "New category"], parentid: "$this->rootId")
}
EOQ;
        $this->executeQuery($query, 'admin');

        $this->assertEquals(200, $this->httpStatus);
        $this->assertEquals(32, strlen($this->queryResult['data']['addCategory']));
    }

    public function testAddCategoryNoPermission()
    {

        $query = <<<EOQ
mutation TestMutation { 
    addCategory (names: ["Neue Kategorie", "New category"], parentid: "$this->rootId")
}
EOQ;
        $this->executeQuery($query, 'customer');

        $this->assertEquals(403, $this->httpStatus);
    }

}
