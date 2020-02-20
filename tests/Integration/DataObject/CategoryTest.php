<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Example\Tests\Integration\DataType;

use OxidEsales\GraphQL\Example\DataType\CategoryFactory;
use PHPUnit\Framework\TestCase;

/**
 * @covers OxidEsales\GraphQL\Example\DataType\Category
 */
final class CategoryTest extends TestCase
{
    public function testBasicCategoryDataType(): void
    {
        $id       = 'random-id';
        $title    = 'Kiteboards';
        $parentid = 'oxrootid';
        $category = CategoryFactory::createCategory(
            $id,
            $title,
            $parentid
        );
        $this->assertSame(
            $id,
            (string) $category->getId()
        );
        $this->assertSame(
            $title,
            $category->getTitle()
        );
        $this->assertSame(
            $parentid,
            (string) $category->getParentid()
        );
    }
}
