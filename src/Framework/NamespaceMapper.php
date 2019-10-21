<?php declare(strict_types=1);

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\GraphQL\Sample\Framework;

use OxidEsales\GraphQL\Framework\NamespaceMapperInterface;

class NamespaceMapper implements NamespaceMapperInterface
{
    public function getControllerNamespaceMapping(): array
    {
        return [
            '\\OxidEsales\\GraphQL\\Sample\\Controller' => __DIR__.'/../Controller/'
        ];
    }

    public function getTypeNamespaceMapping(): array
    {
        return [
            '\\OxidEsales\\GraphQL\\Sample\\DataObject' => __DIR__.'/../DataObject/'
        ];
    }
}
