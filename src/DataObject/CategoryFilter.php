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
use TheCodingMachine\GraphQLite\Annotations\Type;
use TheCodingMachine\GraphQLite\Types\ID;

class CategoryFilter
{
    private $active;
    private $parentid;
    private $title;

    public function __construct(
        ?BoolFilter $active = null,
        ?IDFilter $parentid = null,
        ?StringFilter $title = null
    ) {
        $this->active = $active;
        $this->parentid = $parentid;
        $this->title = $title;
    }

    /**
     * @return array{
     *  oxactive: ?BoolFilter,
     *  oxparentid: ?IDFilter,
     *  oxtitle: ?StringFilter
     * }
     */
    public function getFilters(): array
    {
        return [
            'oxactive' => $this->active,
            'oxparentid' => $this->parentid,
            'oxtitle' => $this->title
        ];
    }
}
