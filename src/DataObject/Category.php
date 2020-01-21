<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Example\DataObject;

use DateTimeImmutable;
use DateTimeInterface;
use OxidEsales\EshopCommunity\Application\Model\Category as CategoryModel;
use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Type;
use TheCodingMachine\GraphQLite\Types\ID;

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
        \DateTimeInterface $timestamp
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->parentid = $parentid;
        $this->timestamp = $timestamp;
    }

    public static function createFromModel(CategoryModel $category): self
    {
        return new self(
            $category->getId(),
            (string)$category->oxcategories__oxtitle,
            (string)$category->oxcategories__oxparentid,
            new \DateTimeImmutable((string)$category->oxcategories__oxtimestamp)
        );
    }

    public function createModel(): CategoryModel
    {
        /** @var CategoryModel */
        $category = oxNew(CategoryModel::class);
        $category->assign([
            'oxid' => $this->id,
            'oxtitle' => $this->title,
            'oxparentid' => $this->parentid
        ]);
        return $category;
    }

    /**
     * @Field()
     */
    public function getId(): ID
    {
        return new ID($this->id);
    }

    /**
     * @Field()
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    public function getParentId(): ID
    {
        return new ID($this->parentid);
    }

    /**
     * @Field()
     */
    public function getTimestamp(): DateTimeInterface
    {
        return $this->timestamp;
    }
}
