<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Example\DataObject;

use OxidEsales\GraphQL\Base\DataObject\BoolFilterInput;
use OxidEsales\GraphQL\Base\DataObject\StringFilterInput;
use OxidEsales\GraphQL\Base\DataObject\IDFilterInput;
use TheCodingMachine\GraphQLite\Annotations\Factory;

class CategoryFilterInputFactory
{
    /**
     * @Factory()
     */
    public static function createCategoryFilterInput(
        ?BoolFilterInput $active = null,
        ?IDFilterInput $parentid = null,
        ?StringFilterInput $title = null
    ): CategoryFilterInput {
        return new CategoryFilterInput(
            $active,
            $parentid,
            $title
        );
    }
}
