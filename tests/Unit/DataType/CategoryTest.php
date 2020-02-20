<?php

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Tests\Unit\DataType;

use PHPUnit\Framework\TestCase;
use OxidEsales\GraphQL\Example\DataType\Category;
use OxidEsales\Eshop\Application\Model\Category as EshopCategoryModel;
use OxidEsales\Eshop\Core\Field;

/**
 * @covers OxidEsales\GraphQL\Example\DataType\Category
 */
class CategoryTest extends TestCase
{
    public function testBasicCategoryGetters()
    {
        $category = new Category(
            new CategoryModelStub(
                'id',
                'parentid',
                'title',
                '2020-01-01 09:09:09'
            )
        );

        $this->assertSame(
            'id',
            (string)$category->getId()
        );
        $this->assertSame(
            'parentid',
            (string)$category->getParentId()
        );
        $this->assertSame(
            'title',
            $category->getTitle()
        );
        $this->assertSame(
            '2020-01-01 09:09:09',
            $category->getTimestamp()->format('Y-m-d H:i:s')
        );
    }
}

// phpcs:disable

class CategoryModelStub extends EshopCategoryModel
{
    public function __construct(
        string $id = 'none',
        string $parentid = 'parentid',
        string $title = 'title',
        string $timestamp = '2020-01-01 09:09:09'
    ) {
        $this->_sCoreTable = 'oxcategories';
        $this->setId($id);
        $this->oxcategories__oxparentid = new Field(
            $parentid,
            Field::T_RAW
        );
        $this->oxcategories__oxtitle = new Field(
            $title,
            Field::T_RAW
        );
        $this->oxcategories__oxtimestamp = new Field(
            $timestamp,
            Field::T_RAW
        );
    }
}
