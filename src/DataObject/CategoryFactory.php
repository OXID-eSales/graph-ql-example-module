<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Example\DataObject;

use OxidEsales\EshopCommunity\Core\Registry;
use OxidEsales\GraphQL\Base\Service\LegacyServiceInterface;
use OxidEsales\GraphQL\Example\Dao\CategoryDaoInterface;
use OxidEsales\GraphQL\Example\DataObject\Category;
use TheCodingMachine\GraphQLite\Annotations\Factory;

class CategoryFactory
{

    /** @var CategoryDaoInterface */
    protected $categoryDao;

    /** @var LegacyServiceInterface */
    private $legacyService = null;

    public function __construct(
        CategoryDaoInterface $categoryDao,
        LegacyServiceInterface $legacyService
    ) {
        $this->categoryDao = $categoryDao;
        $this->legacyService = $legacyService;
    }

    /**
     * @Factory()
     */
    public static function createCategory(
        ?string $id = null,
        string $name,
        ?string $parentid = null
    ): Category {
        if ($id === null) {
            /** @var \OxidEsales\EshopCommunity\Core\UtilsObject */
            $utils = Registry::getUtilsObject();
            $id = $utils->generateUID();
        }
        if ($parentid === null) {
            $parentid = 'oxrootid';
        }
        return new Category(
            $id,
            $name,
            $parentid
        );
    }

    public function addParentCategory(Category $category, string $parentid): void
    {
        $parentCategory = $this->categoryDao->getCategoryById(
            $parentid,
            $this->legacyService->getShopId()
        );
        if ($parentCategory === null) {
            return;
        }
        $category->addParent($parentCategory);
    }

    public function addChildCategories(Category $category, string $parentid): void
    {
        $categories = $this->categoryDao->getCategoriesByParentId(
            $parentid,
            $this->legacyService->getShopId()
        );
        foreach ($categories as $category) {
            $category->addChild($category);
        }
    }
}
