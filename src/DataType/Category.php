<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Example\DataType;

use DateTimeImmutable;
use DateTimeInterface;
use OxidEsales\Eshop\Application\Model\Category as CategoryModel;
use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Type;
use TheCodingMachine\GraphQLite\Types\ID;

/**
 * @Type()
 */
final class Category
{
    /** @var CategoryModel */
    private $category;

    public function __construct(
        CategoryModel $category
    ) {
        $this->category = $category;
    }

    /**
     * @Field
     */
    public function getId(): ID
    {
        return new ID(
            $this->category->getId()
        );
    }

    public function getParentId(): ID
    {
        return new ID(
            $this->category->getFieldData('oxparentid')
        );
    }

    /**
     * @Field
     */
    public function getTitle(): string
    {
        return (string)$this->category->getFieldData('oxtitle');
    }

    /**
     * @Field
     */
    public function getUrl(): string
    {
        return $this->category->getLink();
    }

    /**
     * @Field
     */
    public function getTimestamp(): DateTimeInterface
    {
        return new DateTimeImmutable(
            (string)$this->category->getFieldData('oxtimestamp')
        );
    }
}
