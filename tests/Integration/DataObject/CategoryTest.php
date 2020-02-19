<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Example\Tests\Integration\DataType;

use PHPUnit\Framework\TestCase;
use OxidEsales\GraphQL\Example\DataType\Category;
use OxidEsales\GraphQL\Example\DataType\CategoryFactory;
use DateTimeImmutable;

/**
 * @covers OxidEsales\GraphQL\Example\DataType\Category
 */
class CategoryTest extends TestCase
{
    public function testBasicCategoryDataType()
    {
        $id = 'random-id';
        $title = 'Kiteboards';
        $parentid = 'oxrootid';
        $category = CategoryFactory::createCategory(
            $id,
            $title,
            $parentid
        );
        $this->assertSame(
            $id,
            $category->getId()
        );
        $this->assertSame(
            $title,
            $category->getTitle()
        );
        $this->assertSame(
            $parentid,
            $category->getParentid()
        );
    }
}
