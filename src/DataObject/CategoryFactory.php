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
        ?string $id = null,
        string $title,
        ?string $parentid = null
    ): Category {
        if ($id === null) {
            /** @var \OxidEsales\EshopCommunity\Core\UtilsObject */
            $utils = Registry::getUtilsObject();
            $id = $utils->generateUID();
        }
        return new Category(
            $id,
            $title,
            $parentid ?? 'oxrootid',
            new \DateTimeImmutable("now")
        );
    }
}
