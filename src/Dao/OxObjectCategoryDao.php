<?php
declare(strict_types=1);

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\GraphQl\Sample\Dao;

use OxidEsales\GraphQl\Sample\DataObject\Category;


/**
 * This class is more or less for testing purposes. It uses the
 * traditonal OXID category object to fetch the data instead of
 * a direct database access.
 */
class OxObjectCategoryDao extends CategoryDao implements CategoryDaoInterface
{

    /**
     * @param string $categoryId
     * @param string $lang
     * @param int    $shopId
     *
     * @return Category
     */
    public function getCategory(string $categoryId, string $lang, int $shopId)
    {
        $oxCategory = oxNew(\OxidEsales\Eshop\Application\Model\Category::class);
        $oxCategory->load($categoryId);
        /** @var Category $category */
        $category = new Category();
        $category->setId($categoryId);
        $category->setName($oxCategory->oxcategories__oxtitle->value);
        $category->setParentid($oxCategory->oxcategories__oxparentid->value);

        return $category;
    }
}
