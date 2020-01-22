<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Example\Tests\Unit\DataObject;

use PHPUnit\Framework\TestCase;
use OxidEsales\GraphQL\Example\DataObject\Category;
use DateTimeImmutable;

class CategoryTest extends TestCase
{

    /**
     * @covers OxidEsales\GraphQL\Example\DataObject\Category
     */
    public function testBasicCategoryDataObject()
    {
        $id = 'random-id';
        $title = 'Kiteboards';
        $parentid = 'oxrootid';
        $timestamp = '2019-12-09 15:44:19';
        $category = new Category(
            $id,
            $title,
            $parentid,
            new DateTimeImmutable($timestamp)
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
        $this->assertEquals(
            $timestamp,
            $category->getTimestamp()->format('Y-m-d H:i:s')
        );
    }
}
