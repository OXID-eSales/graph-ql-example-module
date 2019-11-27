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
use OxidEsales\GraphQL\Example\Exception\CategoryNotFound;
use OxidEsales\EshopCommunity\Application\Model\Category as CategoryModel;
use TheCodingMachine\GraphQLite\Annotations\ExtendType;
use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Types\ID;

/**
 * @ExtendType(class=Category::class)
 */
class CategoryExtensionsService
{
    /**
     * @Field()
     */
    public function getParent(Category $child): ?Category
    {
        $category = oxNew(CategoryModel::class);
        if (!$category->load($child->getId())) {
            return null;
        }
        $category = Category::createFromModel($category);
        return $category;
    }

    /**
     * @Field()
     * @return Category[]
     */
    public function getChildren(Category $parent): array
    {
        return [];
    }
}
