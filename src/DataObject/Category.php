<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Example\DataObject;

use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\GraphQL\Example\Dao\CategoryDaoInterface;
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
    private $name;

    /** @var string */
    private $parentid;

    /** @var ?self */
    private $parent;

    /** @var self[] */
    private $children = [];

    public function __construct(
        string $id,
        string $name,
        string $parentid
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->parentid = $parentid;
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
    public function getName(): string
    {
        return $this->name;
    }

    public function addParent(self $category): void
    {
        $this->parent = $category;
    }

    /**
     * parent category
     *
     * @Field()
     */
    public function getParent(): ?self
    {
        if ($this->parent === null) {
            /** @var CategoryFactory */
            $factory = ContainerFactory::getInstance()
                 ->getContainer()
                 ->get(CategoryFactory::class);
            $factory->addParentCategory($this, $this->parentid);
        }
        return $this->parent;
    }

    public function addChild(self $category): void
    {
        $this->children[] = $category;
    }

    /**
     * all child categories
     *
     * @Field()
     * @return Category[]
     */
    public function getChilds(): array
    {
        if (!count($this->children)) {
            /** @var CategoryFactory */
            $factory = ContainerFactory::getInstance()
                 ->getContainer()
                 ->get(CategoryFactory::class);
            $factory->addChildCategories($this, $this->id);
        }
        return $this->children;
    }
}
