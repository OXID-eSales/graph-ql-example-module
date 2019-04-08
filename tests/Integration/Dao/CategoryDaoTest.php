<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 18.03.19
 * Time: 12:43
 */

namespace OxidEsales\GraphQl\Sample\Tests\Integration\Dao;


use OxidEsales\EshopCommunity\Tests\Integration\Internal\TestContainerFactory;
use OxidEsales\GraphQl\Sample\Dao\CategoryDao;
use OxidEsales\GraphQl\Sample\Dao\CategoryDaoInterface;
use PHPUnit\Framework\TestCase;

class CategoryDaoTest extends TestCase
{

    /** @var  CategoryDao $categoryDao */
    private $categoryDao;

    public function setUp()
    {
        $containerFactory = new TestContainerFactory();
        $container = $containerFactory->create();
        $container->compile();
        $this->categoryDao = $container->get(CategoryDaoInterface::class);
    }

    public function testGetCategory()
    {
        $category = $this->categoryDao->getCategory('30e44ab834ea42417.86131097', 'de', 1);
        $this->assertEquals('30e44ab834ea42417.86131097', $category->getId());
        $this->assertEquals('Für Ihn', $category->getName());

    }

    public function testGetCategoryOtherShop()
    {
        $this->expectExceptionMessage('Category with id "30e44ab834ea42417.86131097" not found.');
        $this->categoryDao->getCategory('30e44ab834ea42417.86131097', 'de', 2);

    }

    public function testGetCategoryWithWrongId()
    {
        $this->expectException(\Exception::class);
        $this->categoryDao->getCategory('somenonexistingid', 'de', 1);
    }

    public function testGetRootCategories()
    {
        $rootCategories = $this->categoryDao->getCategories('de', 1);
        $this->assertEquals(7, sizeof($rootCategories));

    }

    public function testGetSubCategories()
    {
        $subCategories = $this->categoryDao->getCategories('de', 1, '30e44ab834ea42417.86131097');
        $this->assertEquals(2, sizeof($subCategories));
    }

    public function testAddRootCategoryDe()
    {
        $id = $this->categoryDao->addCategory(['Deutscher Titel', 'English title'], 1);
        $category = $this->categoryDao->getCategory($id, 'de', 1);
        $this->assertEquals('Deutscher Titel', $category->getName());
        $this->assertNull($category->getParentid());
    }

    public function testAddRootCategoryDeOtherShop()
    {
        $id = $this->categoryDao->addCategory(['Deutscher Titel', 'English title'], 2);
        $notAddedToShop1 = false;
        try {
            $category = $this->categoryDao->getCategory($id, 'de', 1);
        } catch (\Exception $e) {
            $notAddedToShop1 = true;
        }
        $this->assertTrue($notAddedToShop1);

        $category = $this->categoryDao->getCategory($id, 'de', 2);
        $this->assertEquals('Deutscher Titel', $category->getName());
        $this->assertNull($category->getParentid());
    }

    public function testAddRootCategoryEn()
    {
        $id = $this->categoryDao->addCategory(['Deutscher Titel', 'English title'], 1);
        $category = $this->categoryDao->getCategory($id, 'en', 1);
        $this->assertEquals('English title', $category->getName());
        $this->assertNull($category->getParentid());
    }

    public function testAddSubCategoryDe()
    {
        $id = $this->categoryDao->addCategory(['Deutscher Titel', 'English title'], 1,'30e44ab834ea42417.86131097');
        $category = $this->categoryDao->getCategory($id, 'de', 1);
        $this->assertEquals('Deutscher Titel', $category->getName());
        $this->assertEquals('30e44ab834ea42417.86131097', $category->getParentid());
    }

}
