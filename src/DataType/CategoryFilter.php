<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Example\DataType;

use OxidEsales\GraphQL\Base\DataType\IDFilter;
use TheCodingMachine\GraphQLite\Annotations\Factory;

class CategoryFilter
{
    private $parentid = null;

    public function __construct(
        ?IDFilter $parentid = null
    ) {
        $this->parentid = $parentid;
    }

    /**
     * @Factory()
     */
    public static function fromUserInput(
        IDFilter $parentid
    ): self {
        return new self(
            $parentid
        );
    }

    /**
     * @return array{
     *  oxparentid: IDFilter|null,
     * }
     */
    public function getFilters(): array
    {
        return [
            'oxparentid' => $this->parentid,
        ];
    }
}
