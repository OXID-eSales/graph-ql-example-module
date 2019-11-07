<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Example\DataObject;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\GraphQL\Example\DataObject\Category;
use TheCodingMachine\GraphQLite\Annotations\Factory;

class CategoryFactory
{
    /**
     * @Factory()
     */
    public static function createCategory(
        ?string $id = null,
        string $name,
        ?string $parentid = null
    ): Category {
        if ($id === null) {
            $id = Registry::getUtilsObject()->generateUID();
        }
        if ($parentid === null) {
            $parentid = 'oxrootid';
        }
        return new Category(
            $id,
            $name,
            $parentid
        );
    }
}
