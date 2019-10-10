<?php declare(strict_types=1);

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\GraphQl\Sample\Controllers;

use TheCodingMachine\GraphQLite\Annotations\Query;
use OxidEsales\GraphQl\Framework\AppContext;
use OxidEsales\GraphQl\Sample\Dao\CategoryDaoInterface;
use OxidEsales\GraphQl\Sample\DataObject\Category as CategoryDataObject;

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
}
