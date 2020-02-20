<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Example\DataType;

use OxidEsales\Eshop\Application\Model\Category as CategoryModel;
use OxidEsales\Eshop\Core\Registry;
use TheCodingMachine\GraphQLite\Annotations\Factory;

final class CategoryFactory
{
    /**
     * @Factory()
     */
    public static function createCategory(
        ?string $id,
        string $title,
        ?string $parentid = null
    ): Category {
        if ($id === null) {
            /** @var \OxidEsales\Eshop\Core\UtilsObject */
            $utils = Registry::getUtilsObject();
            $id    = $utils->generateUID();
        }
        /** @var CategoryModel */
        $category = oxNew(CategoryModel::class);
        $category->assign([
            'oxid'       => $id,
            'oxtitle'    => $title,
            'oxparentid' => $parentid ?? 'oxrootid',
        ]);

        return new Category(
            $category
        );
    }
}
