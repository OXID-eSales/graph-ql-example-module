<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Example\DataObject;

use TheCodingMachine\GraphQLite\Annotations\Factory;

class CategoryFactory
{
    /**
     * @Factory
     */
    public function createCategory(
        ?string $id,
        ?string $name,
        ?string $parentid = null
    ): Category {

        if ($parentid === null) {
            $parentid = 'oxrootid';
        }

        if ($name == null) {
            $name = '';
        }

        return new Category(
            $id,
            $name,
            $parentId
        );
    }
}
