<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Example\DataObject;

use DateTimeImmutable;
use DateTimeInterface;
use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Type;

/**
 * @Type()
 */
class Category
{
    /** @var string */
    private $id;

    /** @var string */
    private $title;

    /** @var string */
    private $parentid;

    /** @var DateTimeInterface */
    private $timestamp;

    public function __construct(
        string $id,
        string $title,
        string $parentid,
        string $timestamp = "now"
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->parentid = $parentid;
        $this->timestamp = new DateTimeImmutable($timestamp);
    }

    /**
     * unique ID
     *
     * @Field(outputType="ID")
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @Field()
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    public function getParentid(): string
    {
        return $this->parentid;
    }

    /**
     * @Field()
     */
    public function getTimestamp(): DateTimeInterface
    {
        return $this->timestamp;
    }
}
