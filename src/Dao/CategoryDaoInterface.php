<?php declare(strict_types=1);

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\GraphQL\Sample\Dao;

use OxidEsales\GraphQL\Sample\DataObject\Category;

interface CategoryDaoInterface
{
    public function getCategoryById(string $id, int $shopId): Category;
}
