<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Example\DataType;

use OxidEsales\GraphQL\Base\DataType\IDFilter;
use OxidEsales\GraphQL\Example\Exception\CategoryNotFound;
use OxidEsales\GraphQL\Example\Service\CategoryRepository;
use TheCodingMachine\GraphQLite\Annotations\ExtendType;
use TheCodingMachine\GraphQLite\Annotations\Field;

/**
 * @ExtendType(class=Category::class)
 */
final class CategoryRelationService
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
    public function getParent(Category $category): ?Category
    {
        try {
            return $this->repository->getById(
                (string) $category->getParentId()
            );
        } catch (CategoryNotFound $e) {
            return null;
        }
    }

    /**
     * @Field()
     *
     * @return Category[]
     */
    public function getChildren(Category $category): array
    {
        return $this->repository->getByFilter(
            new CategoryFilter(
                new IDFilter($category->getId())
            )
        );
    }
}
