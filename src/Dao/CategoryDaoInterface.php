<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Example\Dao;

use OxidEsales\GraphQL\Example\DataObject\Category;
use OxidEsales\GraphQL\Example\DataObject\CategoryFilter;

interface CategoryDaoInterface
{
    public function getCategoryById(string $id, int $languageId, int $shopId): ?Category;

    /**
     * @return Category[]
     */
    public function getCategories(CategoryFilter $filter, int $languageId, int $shopId): array;

    public function createCategory(Category $category, int $languageId, int $shopId): Category;
}
