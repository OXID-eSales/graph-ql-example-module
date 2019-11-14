<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Example\DataObject;

use OxidEsales\EshopCommunity\Core\Registry;
use TheCodingMachine\GraphQLite\Annotations\Factory;

class CategoryInputFactory
{
    /**
     * @Factory(name="CategoryInput")
     */
    public function createCategoryInput(
        ?string $id,
        ?string $name,
        ?string $parentId = null
    ): CategoryInput {
        if ($id === null) {
            /** @var \OxidEsales\EshopCommunity\Core\UtilsObject */
            $utils = Registry::getUtilsObject();
            $id = $utils->generateUID();
        }
        if ($parentId === null) {
            $parentId = 'oxrootid';
        }

        if ($name == null) {
            $name = '';
        }

        return new CategoryInput(
            $id,
            $name,
            $parentId
        );
    }
}
