<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Example\DataObject;

use OxidEsales\GraphQL\Base\DataObject\IDFilter;
use TheCodingMachine\GraphQLite\Annotations\Factory;

class CategoryFilterFactory
{
    /**
     * @Factory()
     */
    public static function createCategoryFilter(
        IDFilter $parentid
    ): CategoryFilter {
        return new CategoryFilter(
            $parentid
        );
    }
}
