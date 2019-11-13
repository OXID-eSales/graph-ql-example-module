<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Example\DataObject;

use OxidEsales\EshopCommunity\Core\Registry;
use TheCodingMachine\GraphQLite\Annotations\Factory;

class CategoryFactory
{
    /**
     * @Factory()
     */
    public static function createCategory(
        ?string $id,
        string $name,
        ?string $parentid = null
    ): Category {
        if ($id === null) {
            /** @var \OxidEsales\EshopCommunity\Core\UtilsObject */
            $utils = Registry::getUtilsObject();
            $id = $utils->generateUID();
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

