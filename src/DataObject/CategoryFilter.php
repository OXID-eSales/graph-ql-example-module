<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Example\DataObject;

use OxidEsales\GraphQL\Base\DataObject\IDFilter;

class CategoryFilter
{
    private $parentid;

    public function __construct(
        IDFilter $parentid
    ) {
        $this->parentid = $parentid;
    }

    /**
     * @return array{
     *  oxparentid: IDFilter,
     * }
     */
    public function getFilters(): array
    {
        return [
            'oxparentid' => $this->parentid,
        ];
    }
}
