<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Example\Tests\Integration\DataObject;

use PHPUnit\Framework\TestCase;
use OxidEsales\GraphQL\Example\DataObject\Category;
use OxidEsales\GraphQL\Example\DataObject\CategoryFactory;
use DateTimeImmutable;

class CategoryTest extends TestCase
{
    /**
     * @covers OxidEsales\GraphQL\Example\DataObject\Category
     * @covers OxidEsales\GraphQL\Example\DataObject\CategoryFactory
     */
    public function testBasicCategoryDataObject()
    {
        $id = 'random-id';
        $title = 'Kiteboards';
        $parentid = 'oxrootid';
        $category = CategoryFactory::createCategory(
            $id,
            $title,
            $parentid
        );
        $this->assertEquals(
            $id,
            $category->getId()
        );
        $this->assertEquals(
            $title,
            $category->getTitle()
        );
        $this->assertEquals(
            $parentid,
            $category->getParentid()
        );
    }
}
