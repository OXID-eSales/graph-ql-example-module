<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Example\Controller;

use OxidEsales\GraphQL\Example\DataType\Category as CategoryDataType;
use OxidEsales\GraphQL\Example\DataType\CategoryFilter;
use OxidEsales\GraphQL\Example\Service\CategoryRepository;
use TheCodingMachine\GraphQLite\Annotations\Logged;
use TheCodingMachine\GraphQLite\Annotations\Mutation;
use TheCodingMachine\GraphQLite\Annotations\Query;
use TheCodingMachine\GraphQLite\Annotations\Right;

final class Category
{
    /** @var CategoryRepository */
    private $repository;

    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Query()
     */
    public function category(string $id): CategoryDataType
    {
        return $this->repository->getById($id);
    }

    /**
     * @Query()
     *
     * @return CategoryDataType[]
     */
    public function categories(?CategoryFilter $filter = null): array
    {
        return $this->repository->getByFilter(
            $filter ?? new CategoryFilter()
        );
    }

    /**
     * @Mutation()
     * @Logged()
     * @Right("CATEGORY_CREATE")
     */
    public function categoryCreate(CategoryDataType $category): CategoryDataType
    {
        return $this->repository->save($category);
    }
}
