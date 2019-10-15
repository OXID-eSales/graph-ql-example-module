<?php declare(strict_types=1);

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\GraphQL\Sample\Controllers;

use OxidEsales\GraphQL\Framework\AppContext;
use OxidEsales\GraphQL\Sample\Dao\CategoryDaoInterface;
use OxidEsales\GraphQL\Sample\DataObject\Category as CategoryDataObject;
use TheCodingMachine\GraphQLite\Annotations\Query;

class Category
{
    /** @var AppContext */
    protected $context;

    /** @var CategoryDaoInterface */
    protected $categoryDao;

    public function __construct(
        AppContext $context,
        CategoryDaoInterface $userDao
    ) {
        $this->context = $context;
        $this->categoryDao = $userDao;
    }
 
    /**
     * @Query
     */
    public function category(string $id): CategoryDataObject
    {
        return $this->categoryDao->getCategoryById(
            $id,
            $this->context->getCurrentShopId()
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
            $this->context->getCurrentShopId()
        );
    }
}
