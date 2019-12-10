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
        ?string $title,
        ?Category $parent = null
    ): Category {
        if ($id === null) {
            /** @var \OxidEsales\EshopCommunity\Core\UtilsObject */
            $utils = Registry::getUtilsObject();
            $id = $utils->generateUID();
        }
        if ($parent === null) {
            $parentid = 'oxrootid';
        } else {
            $parentid = $parent->getId();
        }
        if ($title == null) {
            $title = '';
        }
        return new Category(
            $id,
            $title,
            $parentid
        );
    }
}
