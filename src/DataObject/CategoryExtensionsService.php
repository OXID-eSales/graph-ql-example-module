<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Example\DataObject;

use OxidEsales\GraphQL\Base\DataObject\IDFilterInput;
use OxidEsales\GraphQL\Base\Service\LegacyServiceInterface;
use OxidEsales\GraphQL\Example\Dao\CategoryDaoInterface;
use TheCodingMachine\GraphQLite\Annotations\ExtendType;
use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Types\ID;

/**
 * @ExtendType(class=Category::class)
 */
class CategoryExtensionsService
{
    /** @var CategoryDaoInterface  */
    private $categoryDao;

    /** @var LegacyServiceInterface  */
    private $legacyService;

    public function __construct(CategoryDaoInterface $categoryDao, LegacyServiceInterface $legacyService)
    {
        $this->categoryDao = $categoryDao;
        $this->legacyService = $legacyService;
    }

    /**
     * @Field()
     */
    public function getParent(Category $child): ?Category
    {
        return $this->categoryDao->getCategoryById(
            $child->getParentid(),
            $this->legacyService->getLanguageId(),
            $this->legacyService->getShopId()
        );
    }

    /**
     * @Field()
     * @return Category[]
     */
    public function getChildren(Category $parent): array
    {
        return $this->categoryDao->getCategories(
            new CategoryFilterInput(
                null,
                new IDFilterInput(
                    new ID($parent->getId())
                ),
                null
            ),
            $this->legacyService->getLanguageId(),
            $this->legacyService->getShopId()
        );
    }
}
