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

    /** @var ?CategoryModel */
    private $category = null;

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
        \DateTimeInterface $timestamp,
        ?CategoryModel $category = null
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->parentid = $parentid;
        $this->timestamp = $timestamp;
        $this->category = $category;
    }

    public static function createFromModel(CategoryModel $category): self
    {
        return new self(
            $category->getId(),
            (string)$category->getFieldData('oxtitle'),
            (string)$category->getFieldData('oxparentid'),
            new \DateTimeImmutable((string)$category->getFieldData('oxtimestamp')),
            $category
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
     * @Field
     */
    public function getUrl(): ?string
    {
        if ($this->category) {
            return $this->category->getLink();
        }
        return null;
    }

    /**
     * @Field()
     */
    public function getTimestamp(): DateTimeInterface
    {
        return $this->timestamp;
    }
}
