<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Example\Tests\Unit\Framework;

use OxidEsales\GraphQL\Example\Framework\NamespaceMapper;
use PHPUnit\Framework\TestCase;

/**
 * @covers OxidEsales\GraphQL\Example\Framework\NamespaceMapper
 */
final class NamespaceMapperTest extends TestCase
{
    public function testNamespaceMapperReturnsCorrectNamespace(): void
    {
        $namespaceMapper = new NamespaceMapper();
        $this->assertCount(
            1,
            $namespaceMapper->getControllerNamespaceMapping()
        );
        $this->assertCount(
            1,
            $namespaceMapper->getTypeNamespaceMapping()
        );
    }
}
