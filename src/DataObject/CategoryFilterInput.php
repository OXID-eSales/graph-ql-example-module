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
use TheCodingMachine\GraphQLite\Annotations\Type;
use TheCodingMachine\GraphQLite\Types\ID;

class CategoryFilterInput
{
    private $active;
    private $parentid;
    private $title;

    public function __construct(
        ?BoolFilterInput $active = null,
        ?IDFilterInput $parentid = null,
        ?StringFilterInput $title = null
    ) {
        $this->active = $active;
        $this->parentid = $parentid;
        $this->title = $title;
    }

    /**
     * @return array{
     *  oxactive: ?BoolFilterInput,
     *  oxparentid: ?IDFilterInput,
     *  oxtitle: ?StringFilterInput
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
