<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Example\DataObject;

use OxidEsales\GraphQL\Base\DataObject\IDFilter;
use OxidEsales\GraphQL\Base\Service\LegacyServiceInterface;
use OxidEsales\GraphQL\Example\Dao\CategoryDaoInterface;
use OxidEsales\GraphQL\Example\DataObject\CategoryFilterFactory;
use OxidEsales\GraphQL\Example\Exception\CategoryNotFound;
use OxidEsales\GraphQL\Example\Service\CategoryRepository;
use OxidEsales\EshopCommunity\Application\Model\Category as CategoryModel;
use TheCodingMachine\GraphQLite\Annotations\ExtendType;
use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Types\ID;

/**
 * @ExtendType(class=Category::class)
 */
class CategoryExtensionsService
{
    /** @var CategoryRepository */
    private $repository;

    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Field()
     */
    public function getParent(Category $child): ?Category
    {
        try {
            return $this->repository->getById((string)$child->getParentId());
        } catch (CategoryNotFound $e) {
            return null;
        }
    }

    /**
     * @Field()
     * @return Category[]
     */
    public function getChildren(Category $parent): array
    {
        return $this->repository->getByFilter(
            CategoryFilterFactory::createCategoryFilter(
                new IDFilter(new ID((string)$parent->getId()))
            )
        );
    }
}
