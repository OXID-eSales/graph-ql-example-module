<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Example\Controller;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\GraphQL\Base\Service\LegacyServiceInterface;
use OxidEsales\GraphQL\Example\Dao\CategoryDaoInterface;
use OxidEsales\GraphQL\Example\DataObject\Category as CategoryDataObject;
use TheCodingMachine\GraphQLite\Annotations\Logged;
use TheCodingMachine\GraphQLite\Annotations\Mutation;
use TheCodingMachine\GraphQLite\Annotations\Query;
use TheCodingMachine\GraphQLite\Annotations\Right;

class Category
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
     * category by ID
     *
     * @Query()
     */
    public function category(string $id): ?CategoryDataObject
    {
        return $this->categoryDao->getCategoryById(
            $id,
            $this->legacyService->getShopId()
        );
    }

    /**
     * category list by parent ID
     *
     * @Query()
     * @return CategoryDataObject[]
     */
    public function categories(string $parentid = null): array
    {
        if ($parentid === null) {
            $parentid = 'oxrootid';
        }
        return $this->categoryDao->getCategoriesByParentId(
            $parentid,
            $this->legacyService->getShopId()
        );
    }

    /**
     * create a category
     *
     * @Mutation()
     * @Logged()
     * @Right("CATEGORY_CREATE")
     */
    public function categoryCreate(CategoryDataObject $category): CategoryDataObject
    {
        return $this->categoryDao->createCategory(
            $category,
            $this->legacyService->getShopId()
        );
    }
}
