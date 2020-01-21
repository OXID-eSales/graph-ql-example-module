<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Example\Controller;

use OxidEsales\EshopCommunity\Application\Model\Category as CategoryModel;
use OxidEsales\EshopCommunity\Application\Model\CategoryList as CategoryListModel;
use OxidEsales\GraphQL\Example\Exception\CategoryNotFound;
use OxidEsales\GraphQL\Example\DataObject\Category as CategoryDataObject;
use OxidEsales\GraphQL\Example\DataObject\CategoryFilter;
use TheCodingMachine\GraphQLite\Annotations\Logged;
use TheCodingMachine\GraphQLite\Annotations\Mutation;
use TheCodingMachine\GraphQLite\Annotations\Query;
use TheCodingMachine\GraphQLite\Annotations\Right;

class Category
{
    /**
     * @Query()
     */
    public function category(string $id): CategoryDataObject
    {
        /** @var CategoryModel */
        $category = oxNew(CategoryModel::class);
        if (!$category->load($id)) {
            throw CategoryNotFound::byCategoryId($id);
        }
        return CategoryDataObject::createFromModel($category);
    }

    /**
     * @Query()
     * @return CategoryDataObject[]
     */
    public function categories(?CategoryFilter $filter = null): array
    {
        /** @var CategoryListModel */
        $categoryList = oxNew(CategoryListModel::class);
        $categoryList->loadList();
        $categories = [];
        /** @var CategoryModel $category */
        foreach ($categoryList as $category) {
            $categories[] = CategoryDataObject::createFromModel($category);
        }
        // categories are special in a case where we may need to filter after the fact
        if ($filter !== null) {
            $parentIdFilter = $filter->getFilters()['oxparentid'];
            $categories = array_filter(
                $categories,
                function (CategoryDataObject $category) use ($parentIdFilter) {
                    return $parentIdFilter->equals() == $category->getParentId();
                }
            );
        }
        return $categories;
    }

    /**
     * @Mutation()
     * @Logged()
     * @Right("CATEGORY_CREATE")
     */
    public function categoryCreate(CategoryDataObject $category): CategoryDataObject
    {
        $category = $category->createModel();
        if (!$category->save()) {
            throw new \Exception();
        }
        return CategoryDataObject::createFromModel($category);
    }
}
