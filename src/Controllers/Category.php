<?php declare(strict_types=1);

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\GraphQL\Sample\Controllers;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\GraphQL\Sample\Dao\CategoryDaoInterface;
use OxidEsales\GraphQL\Sample\DataObject\Category as CategoryDataObject;
use TheCodingMachine\GraphQLite\Annotations\Query;

class Category
{
    /** @var CategoryDaoInterface */
    protected $categoryDao;

    public function __construct(
        CategoryDaoInterface $userDao
    ) {
        $this->categoryDao = $userDao;
    }
 
    /**
     * @Query
     */
    public function category(string $id): CategoryDataObject
    {
        return $this->categoryDao->getCategoryById(
            $id,
            Registry::getConfig()->getShopId()
        );
    }

    /**
     * @Query
     * @return CategoryDataObject[]
     */
    public function categories(string $id = null): array
    {
        if ($id === null) {
            $id = 'oxrootid';
        }
        return $this->categoryDao->getCategoriesByParentId(
            $id,
            Registry::getConfig()->getShopId()
        );
    }
}
