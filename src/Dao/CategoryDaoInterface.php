<?php declare(strict_types=1);

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\GraphQl\Sample\Dao;

use OxidEsales\GraphQl\Sample\DataObject\Category;

interface CategoryDaoInterface
{
    public function getCategoryById(string $id, int $shopId): Category;
}
