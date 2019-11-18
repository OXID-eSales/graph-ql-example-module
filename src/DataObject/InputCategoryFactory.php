<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Example\DataObject;

use OxidEsales\EshopCommunity\Core\Registry;
use TheCodingMachine\GraphQLite\Annotations\Factory;

class InputCategoryFactory
{
    /**
     * @Factory(name="InputCategory")
     */
    public function createInputCategory(
        ?string $id,
        ?string $name,
        ?string $parentId = null
    ): InputCategory {
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

        return new InputCategory(
            $id,
            $name,
            $parentId
        );
    }
}
