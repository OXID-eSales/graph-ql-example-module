<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Example\DataObject;

use OxidEsales\GraphQL\Base\DataObject\BoolFilter;
use OxidEsales\GraphQL\Base\DataObject\StringFilter;
use OxidEsales\GraphQL\Base\DataObject\IDFilter;
use TheCodingMachine\GraphQLite\Annotations\Factory;

class CategoryFilterFactory
{
    /**
     * @Factory()
     */
    public static function createCategoryFilter(
        ?BoolFilter $active = null,
        ?IDFilter $parentid = null,
        ?StringFilter $title = null
    ): CategoryFilter {
        return new CategoryFilter(
            $active,
            $parentid,
            $title
        );
    }
}
