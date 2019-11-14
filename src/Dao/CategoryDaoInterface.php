<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Example\Dao;

use OxidEsales\GraphQL\Example\DataObject\Category;
use OxidEsales\GraphQL\Example\DataObject\CategoryInput;

interface CategoryDaoInterface
{
    public function getCategoryById(string $id, int $languageId, int $shopId): ?Category;

    /**
     * @return Category[]
     */
    public function getCategoriesByParentId(string $parentid, int $languageId, int $shopId): array;

    public function createCategory(CategoryInput $category, int $languageId, int $shopId): Category;

    public function alterName(string $categoryId, string $name, int $languageId, int $shopId): Category;

}
